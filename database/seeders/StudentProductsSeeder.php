<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentProductsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $products = [
            [
                'sku' => 'NHN-001',
                'name' => 'Nguyễn Hoài Nam_Aurora Backpack',
                'description' => 'Balo headless commerce dùng cho bài thực hành Bagisto.',
                'url_key' => 'nguyen-hoai-nam-aurora-backpack',
                'price' => 399000,
            ],
            [
                'sku' => 'NHN-002',
                'name' => 'Nguyễn Hoài Nam_Nebula Watch',
                'description' => 'Đồng hồ demo hiển thị dữ liệu qua GraphQL API.',
                'url_key' => 'nguyen-hoai-nam-nebula-watch',
                'price' => 589000,
            ],
            [
                'sku' => 'NHN-003',
                'name' => 'Nguyễn Hoài Nam_Orbit Bottle',
                'description' => 'Bình nước mẫu phục vụ minh chứng dữ liệu sản phẩm.',
                'url_key' => 'nguyen-hoai-nam-orbit-bottle',
                'price' => 189000,
            ],
        ];

        foreach ($products as $product) {
            DB::table('product_inventories')->whereIn('product_id', function ($query) use ($product) {
                $query->select('id')->from('products')->where('sku', $product['sku']);
            })->delete();

            DB::table('product_categories')->whereIn('product_id', function ($query) use ($product) {
                $query->select('id')->from('products')->where('sku', $product['sku']);
            })->delete();

            DB::table('product_flat')->where('sku', $product['sku'])->delete();
            DB::table('products')->where('sku', $product['sku'])->delete();

            $productId = DB::table('products')->insertGetId([
                'sku' => $product['sku'],
                'type' => 'simple',
                'attribute_family_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_flat')->insert([
                'sku' => $product['sku'],
                'name' => $product['name'],
                'description' => $product['description'],
                'short_description' => $product['description'],
                'url_key' => $product['url_key'],
                'new' => 1,
                'featured' => 0,
                'status' => 1,
                'visible_individually' => 1,
                'price' => $product['price'],
                'thumbnail' => null,
                'created_at' => $now->toDateString(),
                'updated_at' => $now,
                'locale' => 'en',
                'channel' => 'default',
                'product_id' => $productId,
                'min_price' => $product['price'],
                'max_price' => $product['price'],
            ]);

            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => 1,
            ]);

            DB::table('product_inventories')->insert([
                'qty' => 10,
                'product_id' => $productId,
                'inventory_source_id' => 1,
            ]);
        }
    }
}