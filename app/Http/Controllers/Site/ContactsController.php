<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller as RootController;
use Illuminate\Http\Request;
use App\Mail\EmailContactUs;
use App\Mail\EmailAdminContactUs;
use Illuminate\Support\Facades\Mail;
use Config;
use App\Models\Cms;
//use App\Model\ContactType;
use App\Models\Contact;
use App\Models\ContactConversation;

class ContactsController extends RootController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function index( $token = null, Request $request )
    {
        $contact        = new Contact;
        $contact_us     = Cms::where([['id',7],['is_block','N']])->first();
        //$contact_type   = ContactType::where([['is_block','N']])->pluck('title','id');

        if($request->isMethod('POST')) {
            //dd($request);
            $request->validate([
                'mobile' => 'required|numeric',
                'capchacode' => 'required|same:gencode_verify'
            ],
            [
                'capchacode.same' => 'The CAPTCHA is not correct.'
            ]);
			
            $contact_details = [];
            $contact_details['contact_type']= $request->contact_type;
            $contact_details['name']        = $request->name;
            $contact_details['email']       = $request->email;
            $contact_details['mobile']      = $request->mobile;
            $contact_details['is_block']    = 'N';
            $contact_details['created_at']  = date('Y-m-d H:i:s');
            $contact_details['updated_at']  = date('Y-m-d H:i:s');

            if( $contact_data = Contact::create( $contact_details ) ) {
                $ticket_id = 'TIC'.mb_substr($request->contact_type, 0, 1, 'utf-8').$contact_data->id.mt_rand(1000, 9999);
                $contact_details['ticket_id'] = $ticket_id;
                Contact::where('id', $contact_data->id)->update(['ticket_id' => $ticket_id]);

                $conversation_details = [];
                $conversation_details['contact_id'] = $contact_data->id;
                $conversation_details['message']    = $request->message;
                $conversation_details['created_at'] = date('Y-m-d H:i:s');
                $conversation_details['updated_at'] = date('Y-m-d H:i:s');

                $contact_details['message'] = $request->message;

                if( $conversation_data = ContactConversation::create( $conversation_details ) ) {

                    //Config::set('mail.from.address', 'support@germanflorist.de');
					//Config::set('mail.username', 'support@germanflorist.de');
					//Config::set('mail.password', 'hsheevwmbjtwojmq');
					
                    //dd(config('mail'));

                    //Mail::to($request->email)->queue(new EmailContactUs($contact_details));
                    //Mail::to('support@germanflorist.de')->queue(new EmailAdminContactUs($contact_details));
                    //Mail::to('subhajit.floranet19@gmail.com')->queue(new EmailAdminContactUs($contact_details));
                   

                    /*$request->session()->flash('alert-success', 'Thank you for contacting with us. We will get back to you soon.');
                    return redirect()->back();*/

                    return redirect()->route('contact-ticket', $ticket_id);

                }else{
                    $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                    return redirect()->back();
                }
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        return view('site.contact_us')->with(['contact' => $contact, 'contact_us' => $contact_us, /*'contact_type' => $contact_type,*/ 'token' => $token]);
    }

    public function contactTicket( $ticket_id = null,Request $request ) {
        $conversation_details = [];
        if( $ticket_id != '' ){
            $conversation_details = Contact::where('ticket_id', $ticket_id)->first();
            //dd($conversation_details);
        }
        return view('site.contact_ticket')->with(['conversation_details'=>$conversation_details]);
    }

    public function contactStatus( $token = null, Request $request )
    {
        $contact_conversation = new ContactConversation;
        $ticket_id = '';
        $conversation_details = [];

        if( $request->isMethod('POST') ) {

            $tid    = isset($request->ticket_id)?$request->ticket_id:'';
            $temail = isset($request->email_id)?$request->email_id:'';

            return redirect()->route('view-ticket-details', ['ticket_id'=>$tid,'email'=>$temail]);
        }
        return view('site.contact_status')->with(['contact_conversation' => $contact_conversation, 'conversation_details' => $conversation_details, 'ticket_id' => $ticket_id, 'token' => $token]);
    }

    public function viewTicketDetails( Request $request )
    {
        $contact_conversation = new ContactConversation;
        $ticket_id  = isset($request->ticket_id)?$request->ticket_id:'';
        $email      = isset($request->email)?$request->email:'';
        $conversation_details = [];

        if( $ticket_id != '' && $email != '' ) {
            $conversation_details = Contact::where([['ticket_id', $ticket_id],['email', $email]])->first();
        }

        if( $request->isMethod('POST') ) {

            $contact_table_id = 0;
            $tid    = isset($request->tid)?base64_decode($request->tid):'';
            $temail = isset($request->temail)?base64_decode($request->temail):'';
            if( $tid != '' && $temail != '' ){
                $get_ticket_id = Contact::where([['ticket_id', $tid],['email', $temail]])->first();
                if( $get_ticket_id != null ) {
                    $contact_table_id = $get_ticket_id->id;
                }
            }

            $details = [];
            $details['contact_id'] = isset($contact_data->contactid)?base64_decode($contact_data->contactid):$contact_table_id;
            $details['message']    = isset($request->reply_message)?$request->reply_message:'';
            $details['created_at'] = date('Y-m-d H:i:s');
            $details['updated_at'] = date('Y-m-d H:i:s');

            if(ContactConversation::create($details)) {
                $request->session()->flash('alert-success', 'You have successfully submitted your reply.');
            }
            else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            }
            return redirect()->route('view-ticket-details', ['ticket_id'=>$tid,'email'=>$temail]);
        }

        return view('site.view_ticket_details')->with(['conversation_details' => $conversation_details, 'contact_conversation' => $contact_conversation, 'ticket_id' => $ticket_id]);
    }


    public function reloadcaptcha( Request $request ){
        $length = 6;
        $validCharacters = "123456789mnbvcxzasdfghjklpoiuytrewwq";
        $validCharNumber = strlen($validCharacters);
     
        $result = "";
     
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }

        return response()->json(['vcode'=>$result]);
    }
}