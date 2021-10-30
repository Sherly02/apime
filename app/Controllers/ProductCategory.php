<?php
namespace App\Controllers;

use App\Models\posmini\M_ProductCategory;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class ProductCategory extends ResourceController
{
    use ResponseTrait;
    // get list category
    public function index()
    {
        $model              = new M_ProductCategory();
        $data['category']   = $model->orderBy('id_category', 'ASC')->findAll();

        return $this->respond($data, 200);
    }

    // create category
    public function create() {
        $rules      = [
            'category_name' => 'required|min_length[4]',
        ];
        $data       = [
            'category_name' => $this->request->getVar('category_name'),
        ];

        $model      = new M_ProductCategory();
        $model->insert($data);

        $response   = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Successfully add new category!'
            ]
        ];

        return $this->respondCreated($response);
    }

    // get detail category
    public function show($id = null){
        $model  = new M_ProductCategory();
        $data   = $model->where('id_category', $id)->first();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('Category not found!');
        }
    }

    // update category
    public function update($id = null)
    {
        $model      = new M_ProductCategory();
        $json       = $this->request->getJSON();

        if($json){
            $data   = [
                'category_name' => $json->category_name
            ];
        }else{
            $input  = $this->request->getRawInput();
            $data   = [
                'category_name' => $input['category_name']
            ];
        }

        // insert to database
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated!'
            ]
        ];
        return $this->respond($response);
    }

    // delete category
    public function delete($id = '')
    {
        $model  = new M_ProductCategory();
        $data   = $model->find($id);

        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted!'
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('id '. $id . ' is not found!');
        }

    }
}