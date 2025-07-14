<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingAddress;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = [
            [
                'name' => 'Nguyễn Văn An',
                'phone' => '0901234567',
                'province' => 'Hà Nội',
                'district' => 'Quận Ba Đình',
                'ward' => 'Phường Điện Biên',
                'address_detail' => '123 Đường Điện Biên Phủ',
                'is_default' => true,
                'is_active' => true,
                'note' => 'Địa chỉ văn phòng chính'
            ],
            [
                'name' => 'Trần Thị Bình',
                'phone' => '0912345678',
                'province' => 'TP Hồ Chí Minh',
                'district' => 'Quận 1',
                'ward' => 'Phường Bến Nghé',
                'address_detail' => '456 Đường Nguyễn Huệ',
                'is_default' => false,
                'is_active' => true,
                'note' => 'Kho hàng miền Nam'
            ],
            [
                'name' => 'Lê Văn Cường',
                'phone' => '0923456789',
                'province' => 'Đà Nẵng',
                'district' => 'Quận Hải Châu',
                'ward' => 'Phường Hải Châu I',
                'address_detail' => '789 Đường Trần Phú',
                'is_default' => false,
                'is_active' => true,
                'note' => 'Chi nhánh miền Trung'
            ],
            [
                'name' => 'Phạm Thị Dung',
                'phone' => '0934567890',
                'province' => 'Hải Phòng',
                'district' => 'Quận Lê Chân',
                'ward' => 'Phường Đông Hải',
                'address_detail' => '321 Đường Lạch Tray',
                'is_default' => false,
                'is_active' => false,
                'note' => 'Kho tạm thời - không sử dụng'
            ],
            [
                'name' => 'Hoàng Văn Em',
                'phone' => '0945678901',
                'province' => 'Cần Thơ',
                'district' => 'Quận Ninh Kiều',
                'ward' => 'Phường An Hòa',
                'address_detail' => '654 Đường Nguyễn Văn Cừ',
                'is_default' => false,
                'is_active' => true,
                'note' => 'Kho vùng Đồng bằng sông Cửu Long'
            ]
        ];

        foreach ($addresses as $address) {
            ShippingAddress::create($address);
        }
    }
}
