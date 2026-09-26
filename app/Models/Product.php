<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
        'prod_availability',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'prod_availability' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    /**
     * A product belongs to one category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A product can require many ingredients.
     *
     * An ingredient can also belong to many products.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(
            Ingredient::class,
            'product_ingredients'
        )
            ->using(ProductIngredient::class)
            ->withPivot('quantity_required');
    }

    /**
     * Direct access to the product ingredient records.
     */
    public function productIngredients(): HasMany
    {
        return $this->hasMany(ProductIngredient::class);
    }

    /**
     * A product can appear in many online order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * A product can appear in many physical sales details.
     */
    public function salesDetails(): HasMany
    {
        return $this->hasMany(SalesDetail::class);
    }

    /**
     * A product can have many images through the polymorphic images table.
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}