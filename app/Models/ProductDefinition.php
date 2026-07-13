<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDefinition extends Model
{
    use BelongsToCurrentUser, HasFactory;

    public const TYPE_CATEGORY = 'category';
    public const TYPE_BRAND = 'brand';
    public const TYPE_MODEL = 'model';
    public const TYPE_UNIT = 'unit';

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_CATEGORY => 'Kategori',
            self::TYPE_BRAND => 'Marka',
            self::TYPE_MODEL => 'Model',
            self::TYPE_UNIT => 'Birim',
        ];
    }
}
