<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Home & Living', 'home-living', 'Considered objects for a calmer home.', [['Halo Table Lamp', 'halo-table-lamp', 8900, 10900, 'Sculptural ambient lamp with warm dimmable light.', 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80'], ['Arc Ceramic Vase', 'arc-ceramic-vase', 4800, null, 'Hand-finished stoneware with a softly textured glaze.', 'https://images.unsplash.com/photo-1618220179428-22790b461013?auto=format&fit=crop&w=900&q=80'], ['Cloud Linen Throw', 'cloud-linen-throw', 7200, null, 'Washed European linen, soft from the first use.', 'https://images.unsplash.com/photo-1583845112203-454c2254ed4e?auto=format&fit=crop&w=900&q=80']]],
            ['Workspace', 'workspace', 'Tools that make focused work feel effortless.', [['Studio Headphones', 'studio-headphones', 18900, 22900, 'Immersive sound, all-day comfort, and 40-hour battery.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80'], ['Aluminium Keyboard', 'aluminium-keyboard', 12900, null, 'Low-profile wireless keyboard with tactile precision.', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80'], ['Everyday Notebook', 'everyday-notebook', 2400, null, 'Dot-grid pages bound in durable recycled cloth.', 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=900&q=80']]],
            ['Carry', 'carry', 'Built to move beautifully through every day.', [['Weekender Bag', 'weekender-bag', 14800, 17500, 'A roomy recycled-canvas companion for short escapes.', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80'], ['Classic Watch', 'classic-watch', 21000, null, 'Quiet design, sapphire glass, and a supple leather strap.', 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=900&q=80'], ['Essential Sunglasses', 'essential-sunglasses', 9500, null, 'Lightweight acetate frames with polarized lenses.', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=900&q=80']]]];
        foreach ($data as [$name,$slug,$description,$products]) {
            $c = Category::create(compact('name', 'slug', 'description'));
            foreach ($products as $i => [$n,$s,$price,$compare,$desc,$image]) {
                Product::create(['category_id' => $c->id, 'name' => $n, 'slug' => $s, 'price' => $price, 'compare_at_price' => $compare, 'description' => $desc, 'image_url' => $image, 'stock' => 12 + $i * 6, 'featured' => $i === 0, 'active' => true]);
            }
        }
    }
}
