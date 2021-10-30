<?php
namespace App\Models\posmini;

use CodeIgniter\Model;

class M_ProductCategory extends Model
{
    protected $table            = 'category';
    protected $primaryKey       = 'id_category';
    protected $allowedFields    = ['category_name'];
}