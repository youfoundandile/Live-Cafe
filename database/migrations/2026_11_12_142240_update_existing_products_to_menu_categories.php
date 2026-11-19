
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move existing products into their correct subcategories.
        DB::table('products')->where('id', 1)->update(['category_id' => 8]); // Chicken Wrap -> Wraps
        DB::table('products')->where('id', 5)->update(['category_id' => 6]); // Butter Croissant -> Pastries

        DB::table('products')
            ->whereIn('id', [7, 8, 9])
            ->update(['category_id' => 9]); // Americano, Cappuccino, Latte -> Hot Drinks

        // Rename the existing water product and move it to Ice Ice Baby.
        DB::table('products')->where('id', 12)->update([
            'name' => 'Still/Sparkling Water',
            'category_id' => 10,
        ]);
    }

    public function down(): void
    {
        // Restore the original product categories and name.
        DB::table('products')->where('id', 1)->update(['category_id' => 1]);
        DB::table('products')->where('id', 5)->update(['category_id' => 1]);

        DB::table('products')
            ->whereIn('id', [7, 8, 9])
            ->update(['category_id' => 2]);

        DB::table('products')->where('id', 12)->update([
            'name' => 'Bottled Water',
            'category_id' => 2,
        ]);
    }
};
