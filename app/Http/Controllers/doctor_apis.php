<?php


namespace App\Http\Controllers;
use DB;
use Str;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use publicImages;

class doctor_apis extends Controller
{
    public function login(Request $request)
    {
       $email= $request['email'];
       $password=$request['password'];

        $res=DB::table('doctors')->where('email',$email)->where('password',$password)->exists();
        $rec=DB::table('receptionists')->where('email',$email)->where('password',$password)->exists();
        if($res)
        {
            $res1=DB::table('doctors')->where('email',$email)->where('password',$password)->first();
            return [
                "status"=>'success',
                "role"=>'doctor',
                "details"=>$res1
            ];
        }
        else if($rec){
            $rec1=DB::table('receptionists')->where('email',$email)->where('password',$password)->first();
            return [
                "status"=>'success',
                "role"=>'receptionist',
                "details"=>$rec1
            ]; 
        }
        else{
            return [
                "status"=>'failure',
                "details"=>'unauthorised user!'
            ];  
        }
        

    }


    public function submitPatientDetails(Request $request){
        $users = $request->json()->all();
    
        $res=DB::table('patients_1')->insert($users);
        if($res)
        return [
            "status"=>'success',
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function getAllPatientsList(Request $request){
        $res=DB::table('patients_1')->paginate(perPage:20);
        
        if($res)
        return [
            "status"=>'success',    
            "patient_list"=>$res
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function getLastPage(Request $request){
        $count=DB::table('patients_1')->count();
        $lastPage = ceil($count / 20);
        
        if($lastPage)
        return [
            "status"=>'success', 
            "last_page"=>$lastPage,   
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function submitReceptionistDetails(Request $request){
        $rec = $request->json()->all();
    
        $res=DB::table('receptionists')->insert($rec);
        if($res)
        return [
            "status"=>'success',
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function getAllReceptionistList(Request $request){
        $res=DB::table('receptionists')->get();
        if($res)
        return [
            "status"=>'success',    
            "receptionist_list"=>$res
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function editDocProfile(Request $request){
        try{
            $role=$request->role;
            $doc = $request->body;
            //echo $doc;
        if($role=='doctor')
        {
            $res=DB::table('doctors')->where('id',$doc['id'])->update($doc);
            $details=DB::table('doctors')->where('id',$doc['id'])->first();
            return [
                "status"=>'success',
                "role"=>'doctor',
                "details"=>$details
            ];
        }
        else{
            $res=DB::table('receptionists')->where('id',$doc['id'])->update($doc);
            $details=DB::table('receptionists')->where('id',$doc['id'])->first();
            return [
                "status"=>'success',
                "role"=>'receptionist',
                "details"=>$details
            ];
        }
       
    }
        catch(\Illuminate\Database\QueryException $e){
            return [
                "status"=>'failure',
            ];  
        }
    }

    public function editPatientProfile(Request $request){
        try{
            $docDetails = $request->json()->all();
        $res=DB::table('patients_1')->where('mob_num',$docDetails['mob_num'])->update($docDetails);
        $details=DB::table('patients_1')->where('mob_num',$docDetails['mob_num'])->first();
        return [
            "status"=>'success',
            "details"=>$details
        ];
    }
        catch(\Illuminate\Database\QueryException $e){
            return [
                "status"=>'failure',
            ];  
        }
    }

    public function editReceptionistProfile(Request $request){
        try{
            $recDetails = $request->json()->all();
        $res=DB::table('receptionists')->where('mob_num',$recDetails['email'])->update($recDetails);
        $details=DB::table('receptionists')->where('mob_num',$recDetails['mob_num'])->first();
        return [
            "status"=>'success',
            "details"=>$details
        ];
    }
        catch(\Illuminate\Database\QueryException $e){
            return [
                "status"=>'failure',
            ];  
        }
    }
    public function addNotification(Request $request){
        $notication = $request->json()->all();
        $res=DB::table('notifications')->insert($notication);
        if($res)
        return [
            "status"=>'success',
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function getNotifications(Request $request){
        $res=DB::table('notifications')->get();
        if($res)
        return [
            "status"=>'success',
            "notifications_list"=>$res
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function addNextVisitation(Request $request){
        $visitation = $request->json()->all();
        $res=DB::table('visitations')->insert($visitation);
        if($res)
        return [
            "status"=>'success',
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

     public function getVisitations(Request $request){

        $patId=$request->patient_id;
        $res=DB::table('visitations')->where('patient_id',$patId)->get();
        if($res)
        return [
            "status"=>'success',
            "visitations_list"=>$res
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

    public function vaccinePatients(Request $request){
        $res=false;
        $total_pats=DB::table('patients_1')->count();
        $arr=$this->determinePeople($request->due_date);
        if(sizeof($arr['mob_nos']))
        {
           $res=$this->whatsappMsg($arr['mob_nos'],$request->due_date);
        }
        if($res)
        return [
            "status"=>'success',
            "ratio"=>count($arr['mob_nos'])."/".$total_pats,
            "patients"=>$arr['patients']
        ];
        else
        return [
            "status"=>'failure',
            "ratio"=>count($arr['mob_nos'])."/".$total_pats,
            "patients"=>$arr['patients']
        ];
    }

    public function addVaccination(Request $request){
        $vaccination = $request->json()->all();
        $res=DB::table('vaccinations')->insert($vaccination);
        if($res)
        return [
            "status"=>'success',
        ];
        else
        return [
            "status"=>'failure',
        ];  
    }

     public function getVaccination(Request $request){

        $res=DB::table('vaccinations')->get();
        if($res)
        return [
            "status"=>'success',
            "vaccination_list"=>$res
        ];

        else
        return [
            "status"=>'failure',
        ];  
    }

        public function determinePeople($date2){

        $arr=array();
        $arr1=array();
        $res=DB::table('patients_1')->get();
        foreach($res as $patient)
        {
            // $time = strtotime($date2);
            if($patient->dob!=null)
            {
             $shipDate = Carbon::parse($date2)->format('Y-m-d');
             $date = Carbon::createFromFormat('Y-m-d', $shipDate);
            // echo $date."\n";

           // $date = Carbon::createFromFormat('dd-mm-yyyy',$date);
            $dob1=Carbon::createFromFormat('d/m/Y',$patient->dob)->format('Y-m-d');
            $dob=Carbon::createFromFormat('Y-m-d', $dob1);
           // echo "dob".$dob."\n";
            if($dob->copy()->addDays(0)->eq($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered1"."\n";
                // echo $dob->copy()->addDays(0)."==".$date."\n";
                // echo "dob".$dob."\n";
            }
            else if($dob->copy()->addDays(42)==$date)
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered2"."\n";
                // echo "dob".$dob."\n";
                // echo $dob->copy()->addDays(42)."==".$date."\n";
            }
            else if($dob->copy()->addDays(70)->eq($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered3"."\n";
                // echo $dob->copy()->addDays(70)."==".$date."\n";
                // echo "dob".$dob."\n";
            }
            else if($dob->copy()->addDays(98)->eq($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered4"."\n";
                // echo $dob->copy()->addDays(98)."==".$date."\n";
                // echo "dob".$dob."\n";
            }
            else if($dob->copy()->addMonths(6)->gte($date)&&$dob->copy()->addMonths(9)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered5"."\n";
                // echo $dob->copy()->addMonths(6)."==".$date."\n";
                
            }
            else if($dob->copy()->addMonths(12)->gte($date)&&$dob->copy()->addMonths(15)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered6"."\n";
                // echo $dob->copy()->addMonths(12)."==".$date."\n";
            }
            else if($dob->copy()->addMonths(16)->gte($date)&&$dob->copy()->addMonths(18)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered7"."\n";
                // echo $dob->copy()->addMonths(16)."<=".$date."\n";
            }
            else if($dob->copy()->addMonths(18)->gte($date)&&$dob->copy()->addMonths(19)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered8"."\n";
                // echo $dob->copy()->addMonths(18)."<=".$date."\n";
            }
            else if($dob->copy()->addYears(2)->eq($date) && $dob->copy()->addYears(3)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered9"."\n";
                // echo $dob->copy()->addYears(2)."<=".$date."\n";
            }
            else if($dob->copy()->addYears(4)->eq($date) && $dob->copy()->addYears(6)->lte($date))
            {
                array_push($arr1,$patient);
                array_push($arr,"91".$patient->mob_num);
                // echo "entered10"."\n";
                // echo $dob->copy()->addYears(4)."<=".$date."\n";

            }
            else if($dob->copy()->addYears(9)->eq($date) && $dob->copy()->addYears(15)->lte($date))
            {
                
                if($dob->copy()->addYears(10)->eq($date) && $dob->copy()->addYears(12)->lte($date))
                {
                    array_push($arr1,$patient);
                    array_push($arr,"91".$patient->mob_num);
                    // echo "entered11"."\n";
                    // echo $dob->copy()->addYears(10)."<=".$date."\n";
                }
            }
            else{
               // echo "nothing".$dob."\n";
            }
        }
        }
        
        return [
            "patients"=>$arr1,
            "mob_nos"=>$arr
        ];
    }

    public function test($mob_nos,$due_date) {    
    try {
        $response=Http::asForm()->post('https://2factor.in/API/R1/',  [
            "module"=>"TRANS_SMS",
            "apikey"=>"b8c9ee74-5294-11ec-b710-0200cd936042",
            "from"=>"fedcba",
            "to"=>implode(",",$mob_nos),
            "scheduletime"=>$due_date,
            "templatename"=>"visitation",
            "var1"=>'',
            "var2"=>'tomorrow'
    ]);
    $result = json_decode($response->getBody()->getContents(), true);
    if($result)
    {
        return $result;
    }
    else{
        $response->throw();
    }
    }
    catch (\Exception\ClientException $e) {
            $response = $e->getResponse();
            $result =  json_decode($response->getBody()->getContents());
    
          return false;;
    
        }
    
    }

    public function whatsappMsg($mob_nos,$due_date) {    
        try {
            $response=Http::asForm()->post('http://private.itswhatsapp.com/wapp/api/send?apikey=7e82684d8ab64de4a063b5cc5d1bd27d&mobile='.implode(",",$mob_nos).'&msg=testmsg');
        $result = json_decode($response->getBody()->getContents(), true);
        if($result)
        {
            return $result;
        }
        else{
            $response->throw();
        }
        }
        catch (\Exception\ClientException $e) {
                $response = $e->getResponse();
                $result =  json_decode($response->getBody()->getContents());
        
              return false;;
        
            }
        
        }

        public function storeImage(Request $request){
            try{
                //$path = $request->photo->store('images');
                $imageName=Str::random(32).".".$image->getClientOriginalName();
                $store=DB::table('patients_1')->where('id',$request->id)->update(["pat_image"=>$imageName]);
                $stored=Storage::disk('public')->put($imageName,file_get_contents($image));
                echo $stored;
                
                //echo $stored;
                return response()->json(['message'=>'post successfully created'],200);

            }catch(\Exception $e)
            {
                return response()->json(['message'=>'something really went wrong'],500);
            }
        }

        public function getImage(Request $request)
    {
        $path = public_path().'/uploads/images/'.$request->path;
        
        return Response::download($path);  
    }
    
    public function updateReceptionistDetails(Request $request)
    {
        $rec=DB::table('receptionists')->where('email',$request->email)->where('password',$request->password)->update(["status"=>$request->status]);
       
        if($rec)
        {
            return [
                "status"=>"success"
            ];
        }
        else{
            return [
                "status"=>"failed"
            ];
        }
        
    }

    public function checkReceptionistStatus(Request $request){
        $rec=DB::table('receptionists')->where('email',$request->email)->where('password',$request->password)->first();
        return [
            "status"=>$rec->status
        ];
    }
}
