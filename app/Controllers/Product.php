<?php
namespace App\Controllers;

use App\Models\posmini\M_Product;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Product extends ResourceController
{
    use ResponseTrait;
    // get list category
    public function index()
    {
        $model              = new M_Product();
        $data['products']   = $model->orderBy('id_product', 'ASC')->findAll();

        return $this->respond($data, 200);
    }

    // create category
    public function create() {
        $rules      = [
            'id_category'   => 'required',
            'product_name'  => 'required|min_length[4]',
            'description'   => 'required|min_length[4]',
            'price'         => 'required',
            'product_image' => 'uploaded[product_image]|max_size[product_image, 1024]|is_image[product_image]',
        ];

        if (!$this->validate($rules)){
            return $this->fail($this->validator->getErrors());
        } else {
            // get image
            $image      = $this->request->getFile('product_image');
            if (!$image->isValid()){
                return $this->fail($image->getErrorString());
            }

            $image->move('./assets/uploads');

            $data       = [
                'id_category'   => $this->request->getVar('id_category'),
                'product_name'  => $this->request->getVar('product_name'),
                'description'   => $this->request->getVar('description'),
                'price'         => $this->request->getVar('price'),
                'product_image' => $image->getName()
            ];

            $model      = new M_Product();
            $model->insert($data);

            $response   = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Successfully add new product!'
                ]
            ];

            return $this->respondCreated($response);
        }
    }

    // get detail category
    public function show($id = null){
        $model  = new M_Product();
        $data   = $model->select('*,category_name');
        $data   = $model->join('category', 'category.id_category = product.id_category');
        $data   = $model->where('id_product', $id)->first();

        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('Category not found!');
        }
    }

    // update category
    public function update($id = null)
    {
        $model      = new M_Product();
        $json       = $this->request->getJSON();

        if($json){
            $rules      = [
                'id_category'   => 'required',
                'product_name'  => 'required|min_length[4]',
                'description'   => 'required|min_length[4]',
                'price'         => 'required'
            ];

            $imageName  = dot_array_search('product_image.name', $_FILES);
            if ($imageName != ''){
                $img  = [
                  'product_image' => 'uploaded[product_image]|max_size[product_image, 1024]|is_image[product_image]'
                ];
                $rules  = array_merge($rules, $img);
            }

            if (!$this->validate($rules)){
                return $this->fail($this->validator->getErrors());
            } else {
                $data       = [
                    'id_category'   => $this->request->getVar('id_category'),
                    'product_name'  => $this->request->getVar('product_name'),
                    'description'   => $this->request->getVar('description'),
                    'price'         => $this->request->getVar('price')
                ];

                if ($imageName != ''){
                    $image      = $this->request->getFile('product_image');
                    if (!$image->isValid()){
                        return $this->fail($image->getErrorString());
                    }

                    $image->move('./assets/uploads');
                    $data['product_image']  = $image->getName();
                }
            }
        } else {
            $input  = $this->request->getRawInput();
            $data   = [
                'id_category'   => $input['id_category'],
                'product_name'  => $input['product_name'],
                'description'   => $input['description'],
                'price'         => $input['price']
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
        $model  = new M_Product();
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