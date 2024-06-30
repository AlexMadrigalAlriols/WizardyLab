<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\StoreRequest;
use App\Mail\ContactMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        App::setLocale($request->input("lang", "en"));
            $faqs = [
                [
                    "question" => "What is an ERP?",
                    "answer" => "An ERP is a software system that integrates and manages the main functions of an organization, such as accounting, human resources, sales, purchasing, inventory, and production. Its goal is to facilitate the flow of information between all departments."
                ],
                [
                    "question" => "Why do businesses need an ERP system?",
                    "answer" => "Businesses need an ERP system to streamline processes, improve data accuracy, enhance productivity, and provide real-time insights into operations. This leads to better decision-making and overall efficiency."
                ],
                [
                    "question" => "What are the key features of an ERP system?",
                    "answer" => "Key features of an ERP system include financial management, supply chain management, inventory management, human resources, customer relationship management (CRM), and reporting/analytics."
                ],
                [
                    "question" => "How does an ERP system improve business processes?",
                    "answer" => "An ERP system improves business processes by automating repetitive tasks, reducing manual data entry, ensuring data consistency, and providing a unified view of business operations, which helps in making informed decisions."
                ],
                [
                    "question" => "What types of businesses can benefit from an ERP system?",
                    "answer" => "Almost any type of business, regardless of size or industry, can benefit from an ERP system. This includes manufacturing, retail, healthcare, finance, education, and more."
                ],
                [
                    "question" => "What are the differences between on-premise and cloud-based ERP systems?",
                    "answer" => "On-premise ERP systems are installed locally on a company's own servers, while cloud-based ERP systems are hosted on the vendor's servers and accessed via the internet. Cloud-based ERPs often offer advantages in terms of scalability, cost, and accessibility."
                ],
                [
                    "question" => "How long does it take to implement an ERP system?",
                    "answer" => "The time to implement an ERP system varies widely depending on the complexity of the business processes, the size of the organization, and the level of customization required. It can range from a few months to over a year."
                ],
                [
                    "question" => "What are the potential challenges of implementing an ERP system?",
                    "answer" => "Potential challenges include high initial costs, resistance to change from employees, data migration issues, system customization complexities, and the need for thorough training and support."
                ],
                [
                    "question" => "How can an ERP system enhance data security?",
                    "answer" => "An ERP system enhances data security by centralizing data management, providing user access controls, encrypting sensitive information, and ensuring compliance with industry regulations and standards."
                ],
                [
                    "question" => "How does an ERP system support decision-making?",
                    "answer" => "An ERP system supports decision-making by providing real-time data and analytics, generating comprehensive reports, and offering predictive insights. This helps managers and executives make informed, data-driven decisions."
                ]
            ];
        return view('landing', compact("faqs"));
    }

    public function store(StoreRequest $request){
        Mail::to("pablo.gimenez.ricart@gmail.com")->send(new ContactMailable($request->all()));
        toast("Message send", "success");
        return Redirect::to(URL::previous() . "#contact");
    }
}
