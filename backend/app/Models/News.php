<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'title',
        'category',
        'url',
        'image_url',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the validation rules for the model.
     *
     * @param  string|null  $id
     * @return array
     */
    public static function getValidationRules($id = null)
    {
        $rules = [
            'date' => 'required|date|before_or_equal:today',
            'title' => 'required|string|max:200',
            'category' => 'required|string|in:technology,business,sports,entertainment,health,science,politics,world',
            'url' => 'required|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120', // 5MB max
            'status' => 'sometimes|string|in:published,unpublished',
        ];

        // For update, make image optional
        if ($id) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,gif,webp|max:5120';
        }

        return $rules;
    }

    /**
     * Get the validation messages for the model.
     *
     * @return array
     */
    public static function getValidationMessages()
    {
        return [
            'date.required' => 'Date is required',
            'date.date' => 'Please provide a valid date',
            'date.before_or_equal' => 'Date cannot be in the future',
            'title.required' => 'Title is required',
            'title.max' => 'Title must not exceed 200 characters',
            'category.required' => 'Category is required',
            'category.in' => 'Please select a valid category',
            'url.required' => 'URL is required',
            'url.url' => 'Please provide a valid URL',
            'url.max' => 'URL must not exceed 500 characters',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'Image must be a file of type: jpeg, png, gif, webp',
            'image.max' => 'Image size must not exceed 5MB',
            'status.in' => 'Status must be either published or unpublished',
        ];
    }

    /**
     * Scope a query to only include published news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include unpublished news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status', 'unpublished');
    }
}
