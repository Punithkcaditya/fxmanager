<?php
namespace App\Controllers;
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Reader\csv;
use PhpOffice\PhpSpreadsheet\Reader\xlsx as excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\xlsx;

class EcxelController extends BaseController
{
    public function index()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot[0])) {
            return redirect()->to("/");
        }
        $data["session"] = $session;
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data['view'] = 'admin/excelupload';
        return view('templates/default', $data);
    }

    public function export()
    {
        try {
            $dataguest = $this->guest_model
                ->orderBy("guest_list_id", "DESC")
                ->findAll();
            $file_name = 'data.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Email');
            $sheet->setCellValue('C1', 'Phone');
            $sheet->setCellValue('D1', 'Sangeet');
            $sheet->setCellValue('E1', 'Ladies Mehendi');
            $sheet->setCellValue('F1', 'Tel Baan');
            $sheet->setCellValue('G1', 'Baraat Wedding Reception');
            $sheet->setCellValue('H1', 'No of Guest');
            $sheet->setCellValue('I1', 'Guest Comment');
            $sheet->setCellValue('J1', 'Created At');
            $count = 2;
            foreach ($dataguest as $row) {
                $sheet->setCellValue('A' . $count, $row['name']);
                $sheet->setCellValue('B' . $count, $row['email']);
                $sheet->setCellValue('C' . $count, $row['phone']);
                $sheet->setCellValue('D' . $count, $row['Sangeet']);
                $sheet->setCellValue('E' . $count, $row['Ladies_Mehendi']);
                $sheet->setCellValue('F' . $count, $row['Tel_Baan']);
                $sheet->setCellValue('G' . $count, $row['Baraat_Wedding_Reception']);
                $sheet->setCellValue('H' . $count, $row['no_of_guest']);
                $sheet->setCellValue('I' . $count, $row['guest_comment']);
                $sheet->setCellValue('J' . $count, $row['created_at']);
                $count++;
            }

            $writer = new XLsx($spreadsheet);
            $writer->save($file_name);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . basename($file_name) . "");
            header('Expires:0');
            header('Cache-Control: must-revalidate');
            header('Pragma:public');
            header('Content-Length' . filesize($file_name));
            flush();
            readfile($file_name);
            return redirect()->to("guestlist");
            exit;
        } catch (Exception $e) {
            return redirect()->to("/");
        }
    }

    public function upload()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot[0])) {
            return redirect()->to("/");
        }
        if ($this->request->getMethod() == "post") {

            $rules = $this->validate(['file' => 'uploaded[filename]|max_size[filename,500]|ext_in[filename,csv,xlsx]',
            ]);
            if ($rules == 1) {
                $filename = $this->request->getFile('filename');
                $name = $filename->getName();
                $tempName = $filename->getTempName();
                $arr_file = explode('.', $name);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new Csv();
                } elseif ('xlsx' == $extension) {
                    $reader = new excel();
                } else {
                    $session->setFlashdata("error", "Select Valid File");
                    return redirect()->to('Admindashboard');
                }
            } else {
                $session->setFlashdata("error", "Select Valid File");
                return redirect()->to('Admindashboard');
            }
            $spreadsheet = $reader->load($tempName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            if (!empty($sheetData)) {
                for ($i = 1; $i < count($sheetData); $i++) {
                    $name = $sheetData[$i][0];
                    $email = $sheetData[$i][1];
                    $phone = $sheetData[$i][2];
                    $Sangeet = $sheetData[$i][3];
                    $Ladies_Mehendi = $sheetData[$i][4];
                    $Tel_Baan = $sheetData[$i][5];
                    $Baraat_Wedding_Reception = $sheetData[$i][6];

                    switch ($Sangeet) {
                        case 1:
                            $Sangeetval = 'Yes';
                            break;
                        case 0:
                            $Sangeetval = 'No';
                            break;
                        default:
                            $Sangeetval = '';
                    }
                    switch ($Ladies_Mehendi) {
                        case 1:
                            $Ladies_Mehendival = 'Yes';
                            break;
                        case 0:
                            $Ladies_Mehendival = 'No';
                            break;
                        default:
                            $Ladies_Mehendival = '';
                    }
                    switch ($Tel_Baan) {
                        case 1:
                            $Tel_Baanval = 'Yes';
                            break;
                        case 0:
                            $Tel_Baanval = 'No';
                            break;
                        default:
                            $Tel_Baanval = '';
                    }
                    switch ($Baraat_Wedding_Reception) {
                        case 1:
                            $Baraat_Wedding_Receptionval = 'Yes';
                            break;
                        case 0:
                            $Baraat_Wedding_Receptionval = 'No';
                            break;
                        default:
                            $Baraat_Wedding_Receptionval = '';
                    }

                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'Sangeet' => $Sangeetval,
                        'Ladies_Mehendi' => $Ladies_Mehendival,
                        'Tel_Baan' => $Tel_Baanval,
                        'Baraat_Wedding_Reception' => $Baraat_Wedding_Receptionval,
                    ];
                    $this->guest_model->insertValue($data);
                }
                $session->setFlashdata("success", "Uploaded Successfully");
                return redirect()->to("guestimport");
            } else {
                $session->setFlashdata("error", "Excel Sheet Is Empty");
                return redirect()->to('Admindashboard');
            }

        } else {
            $session->setFlashdata("error", "Try Again");
            return redirect()->to('Admindashboard');
        }

    }
}

// echo '<pre>';
// print_r($newName);
// exit;
