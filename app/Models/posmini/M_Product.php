<?php
namespace App\Models\posmini;

use CodeIgniter\Model;

class M_Product extends Model
{
    protected $table            = 'product';
    protected $primaryKey       = 'id_product';
    protected $allowedFields    = [
        'id_category',
        'product_name',
        'description',
        'price',
        'product_image'
    ];
}