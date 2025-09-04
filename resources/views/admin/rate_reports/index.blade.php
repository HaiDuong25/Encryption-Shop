@extends('admin.layouts.main')
@section('title','Quản lý Báo cáo Đánh giá')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
            <div class="d-flex align-items-center gap-2">
              <a href="{{ route('rates.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                <i class="ri-arrow-left-line me-1"></i> Quay lại đánh giá
              </a>
              <h5 class="mb-0">Báo cáo đánh giá</h5>
            </div>
            <form class="d-flex flex-wrap gap-2" method="GET">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm nội dung / user" class="form-control" style="min-width:200px;">
              <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                @foreach(['pending'=>'Pending','actioned'=>'Actioned','dismissed'=>'Dismissed'] as $k=>$v)
                  <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
              </select>
              <select name="reason" class="form-select">
                <option value="">-- Lý do --</option>
                @foreach($reasons as $r)
                  <option value="{{ $r }}" @selected(request('reason')===$r)>{{ $r }}</option>
                @endforeach
              </select>
              <input type="number" name="rate_id" value="{{ request('rate_id') }}" placeholder="Rate ID" class="form-control" style="width:120px;">
              <button class="btn btn-primary"><i class="ri-filter-2-line me-1"></i>Lọc</button>
              @if(request()->query())
                <a href="{{ route('admin.rate-reports.index') }}" class="btn btn-outline-secondary">Reset</a>
              @endif
            </form>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle small">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Rate</th>
                  <th>User</th>
                  <th>Reason</th>
                  <th>Note</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reports as $rep)
                  <tr data-report-id="{{ $rep->id }}">
                    <td>{{ $rep->id }}</td>
                    <td>
                      #{{ $rep->rate_id }}<br>
                      <small class="text-muted">{{ Str::limit($rep->rate?->content,40) }}</small>
                    </td>
                    <td>{{ $rep->user?->name ?? 'N/A' }}<br><small class="text-muted">ID: {{ $rep->user_id }}</small></td>
                    <td><span class="badge bg-light text-dark">{{ $rep->reason_text }}</span></td>
                    <td style="max-width:200px; white-space:pre-line;">{{ $rep->note }}</td>
                    <td>
                      <span class="badge status-badge {{ $rep->status==='pending'?'bg-warning text-dark':($rep->status==='actioned'?'bg-success':'bg-secondary') }}">{{ $rep->status }}</span>
                    </td>
                    <td>{{ $rep->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-nowrap">
                      <button class="btn btn-sm btn-outline-success update-status-btn" data-status="actioned" @disabled($rep->status!=='pending')>Xử lý</button>
                      <button class="btn btn-sm btn-outline-secondary update-status-btn" data-status="dismissed" @disabled($rep->status!=='pending')>Bỏ qua</button>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="8" class="text-center text-muted">Không có báo cáo.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($reports->hasPages())
            <div class="mt-3">{{ $reports->links() }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  function toast(msg,type='success'){
    let box=document.getElementById('admin-toast-box');
    if(!box){box=document.createElement('div');box.id='admin-toast-box';Object.assign(box.style,{position:'fixed',top:'18px',right:'18px',zIndex:9999,display:'flex',flexDirection:'column',gap:'8px'});document.body.appendChild(box);}    
    const el=document.createElement('div');
    el.textContent=msg;
    Object.assign(el.style,{background:type==='success'?'#16a34a':(type==='error'?'#dc2626':'#2563eb'),color:'#fff',padding:'8px 14px',borderRadius:'6px',fontSize:'13px',boxShadow:'0 4px 12px rgba(0,0,0,.15)',opacity:0,transform:'translateY(-4px)',transition:'all .25s'});
    box.appendChild(el);requestAnimationFrame(()=>{el.style.opacity=1;el.style.transform='translateY(0)';});
    setTimeout(()=>{el.style.opacity=0;el.style.transform='translateY(-4px)';setTimeout(()=>el.remove(),250);},2800);
  }
  document.addEventListener('click',function(e){
    const btn=e.target.closest('.update-status-btn');
    if(!btn) return;
    const tr=btn.closest('tr');
    const id=tr.dataset.reportId; const status=btn.dataset.status;
    btn.disabled=true;
    fetch(`/admin/rate-reports/${id}`,{method:'PATCH',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify({status})})
      .then(r=>r.json()).then(data=>{
        if(data.success){
          const badge=tr.querySelector('.status-badge');
          badge.textContent=data.report.status;
            badge.className='badge status-badge '+(data.report.status==='actioned'?'bg-success':(data.report.status==='dismissed'?'bg-secondary':'bg-warning text-dark'));
          tr.querySelectorAll('.update-status-btn').forEach(b=>b.disabled=true);
          toast('Cập nhật thành công');
        } else { btn.disabled=false; toast(data.message||'Lỗi','error'); }
      }).catch(()=>{ btn.disabled=false; toast('Lỗi mạng','error'); });
  });
})();
</script>
@endsection
