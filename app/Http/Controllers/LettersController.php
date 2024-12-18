<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letters;
use App\Helpers\Encrypt;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LettersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Letters();

        return view('addLetters', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [            
            'title' 	        => 'required',
            'attachment'        => 'integer|min:0',
            'sender_name'       => 'required',
            'recipient_name'    => 'required',
            'recipient_address' => 'required',
            'content'           => 'required',
        ]);

        $bulan    	    = date('n');
        $romawi    	    = $this->getRomawi($bulan);
        $year     	    = date('Y');
        $getNumber      = Letters::where('year', $year)->lockForUpdate()->max('number');
        $number         = ($getNumber ?? 0) + 1;
        $kodeSurat      = sprintf("%04s", $number);
        $nomor          = "/BBArt/".$romawi."/".$year;
        $nomorSurat     = $kodeSurat.$nomor;
		$slug 	        = preg_replace("/[^a-zA-Z0-9.]/", "_", $request->title);

		$data = array(
            'year'              => $year,
            'number'            => $number,
            'letter_number'     => $nomorSurat,
            'attachments' 	    => $request->attachment ?? 0,
            'title' 	        => $request->title,
            'slug'              => $slug,
            'letter_date'       => date('Y-m-d'),
            'recipient_name'    => $request->recipient_name,
            'recipient_address' => $request->recipient_address,
            'sender_name' 	    => $request->sender_name,
            'content'           => $request->content,
        );
        
		$model = Letters::create($data);
		return json_encode($data);
    }

    public function getRomawi($bln){
        switch ($bln){
			case 1: 
				return "I";
			break;
			case 2:
				return "II";
			break;
			case 3:
				return "III";
			break;
			case 4:
				return "IV";
			break;
			case 5:
				return "V";
			break;
			case 6:
				return "VI";
			break;
			case 7:
				return "VII";
			break;
			case 8:
				return "VIII";
			break;
			case 9:
				return "IX";
			break;
			case 10:
				return "X";
			break;
			case 11:
				return "XI";
			break;
			case 12:
				return "XII";
			break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $encrypt = new Encrypt;

        $letterId = $encrypt->encrypt_decrypt($id, 'decrypt');

        $dataLetter = Letters::where('id', $letterId)->first();

        $data['model'] = $dataLetter;
        $data['encrypt'] = $id;

        QrCode::size(400)->generate(route('print', $id), public_path().'/images/qrcodes/'.$id.'_'.$dataLetter->slug.'.svg');
        return view('detailLetter', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [            
            'title' 	        => 'required',
            'attachments'       => 'integer|min:0',
            'sender_name'       => 'required',
            'recipient_name'    => 'required',
            'recipient_address' => 'required',
            'content'           => 'required',
        ]);

        $bulan    	    = date('n');
        $romawi    	    = $this->getRomawi($bulan);
        $year     	    = date('Y');
        $getNumber      = Letters::select('number','letter_number')->where('id', $request->letterId)->first();
        $number         = $getNumber->number;
        $kodeSurat      = sprintf("%04s", $number);
        $nomor          = "/BBArt/".$romawi."/".$year;
        $nomorSurat     = $kodeSurat.$nomor;
		$slug 	        = preg_replace("/[^a-zA-Z0-9.]/", "_", $request->title);

		$data = array(
            'year'              => $year,
            'number'            => $number,
            'letter_number'     => $nomorSurat,
            'attachments' 	    => $request->attachment ?? 0,
            'title' 	        => $request->title,
            'slug'              => $slug,
            'letter_date'       => date('Y-m-d'),
            'recipient_name'    => $request->recipient_name,
            'recipient_address' => $request->recipient_address,
            'sender_name' 	    => $request->sender_name,
            'content'           => $request->content,
        );

        Letters::where('id', $request->letterId)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getLetters(Request $request)
    {
        $encrypt = new Encrypt;

        if($request->ajax()) {				
			$model = Letters::orderBy('number', 'asc')->get();
		
            return Datatables::of($model)
			->editColumn('id', function ($model) use ($encrypt){
				return $encrypt->encrypt_decrypt($model->id, 'encrypt');
            })
			->editColumn('letter_date', function($model)
            {
                return date('d-M-Y', strtotime($model->letter_date));
            })	
            ->editColumn('action', function($model){
                $encrypt = new Encrypt;

                $encrypted = $encrypt->encrypt_decrypt($model->id, 'encrypt');
                return view('action', ['encrypted' => $encrypted]);
            })
			->make(true);
        }
    }

    public function print($id)
    {
		$encrypt = new Encrypt;
		$letterId = $encrypt->encrypt_decrypt($id, 'decrypt');
        $dataLetter = Letters::query()
						->select(
							'letters.*',					
						)
						->where('id', $letterId)
                        ->firstOrFail();
		
        $bladePrint = 'print';
		$pdf = Pdf::loadView($bladePrint, ['dataLetter' => $dataLetter,
											'encrypt' => $id]);
		$pdf->setPaper('A4', 'portrait');
		$newname=$dataLetter->slug.'.pdf';
		$pdf->getDomPDF()->set_option("enable_php", true);
		return $pdf->stream($newname);
    }
}
