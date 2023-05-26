<?php

namespace App\Controllers;


use App\Models\Items;

class Ajaxupload extends BaseController
{  

    protected $request;
    protected $auth_model;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->item_model = new Items;
        $this->data = ['session' => $this->session,'request'=>$this->request];
    }

    public function index() {
        $data=[];
        $data['page_title']="upload";
        // $this->data['page_title']="upload";
        return view('pages/departments/uploadimg', $data);
    }

    public function upload() {

        helper(['form', 'url']);


        $upload_data = array(
            'upload_path' => dirname($_SERVER["SCRIPT_FILENAME"]) . "/files/items",
            'upload_url' => base_url() . "/files/items",
            'allowed_types' => "gif|jpg|png|jpeg",
            'overwrite' => TRUE,
            'max_size' => "2048KB",
            'width' => 400,
            'height' => 1,
            'quality' => "100%",
            'file_name' => rand(1, 1000000) . microtime(true) . '.jpg'
        );



        $file_name = null;
     

        $this->load->library('upload', $upload_data);

        if ($this->upload->do_upload('userfile')) {

            $uploaded_file = $this->upload->data();

            $file_name = $uploaded_file['file_name'];



            $image_config["image_library"] = "gd2";
            $image_config["source_image"] = $upload_data["upload_path"] . "/" . $file_name;
            $image_config['create_thumb'] = FALSE;
            $image_config['maintain_ratio'] = TRUE;
            $image_config['new_image'] = $upload_data["upload_path"] . "/" . $file_name;
            $image_config['quality'] = "100%";
            $image_config['width'] = 400;
            $image_config['height'] = 1;
            $dim = (intval($upload_data["width"]) / intval($upload_data["height"])) - ($image_config['width'] / $image_config['height']);
            $image_config['master_dim'] = ($dim > 0) ? "height" : "width";

            $this->load->library('image_lib');
            $this->image_lib->initialize($image_config);

            if (!$this->image_lib->resize()) { //Resize image
            }
        }
        if ($file_name == "") {
            $file_name = 'listing-default.png';



            $data = array(
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image' => base_url() . 'files/items/' . $file_name
            );




            $this->Items->insert_item($data);
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('result' => 0)));
        } else {
            $data = array(
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image' => base_url() . 'files/items/' . $file_name
            );




            $this->Items->insert_item($data);
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('result' => 1)));
        }
    }

}
