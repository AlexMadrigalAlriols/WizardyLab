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
    public function index(Request $request, string $lang = "en")
    {
        if(!in_array($lang, ["en", "es"])){
            $lang = "en";
        }

		$subdomain = explode('.', $request->getHost());
		if(count($subdomain) == 3 && $subdomain[0] != "www") {
            return Redirect::to(route('login'));
        }

        App::setLocale($lang);

        $langList = ["en"=>"en", "es"=>"es"];
        unset($langList[$lang]);
        return view('landing', compact("lang", "langList"));
    }

    public function erp(Request $request, string $lang = "en")
    {
        if(!in_array($lang, ["en", "es"])){
            $lang = "en";
        }

		$subdomain = explode('.', $request->getHost());
		if(count($subdomain) == 3 && $subdomain[0] != "www") {
            return Redirect::to(route('login'));
        }

        App::setLocale($lang);
        $faqs = [
            "en" => [
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
            ],
            "es" => [
                [
                    "question" => "¿Qué es un ERP?",
                    "answer" => "Un ERP es un sistema de software que integra y gestiona las principales funciones de una organización, como contabilidad, recursos humanos, ventas, compras, inventario y producción. Su objetivo es facilitar el flujo de información entre todos los departamentos."
                ],
                [
                    "question" => "¿Por qué las empresas necesitan un sistema ERP?",
                    "answer" => "Las empresas necesitan un sistema ERP para optimizar procesos, mejorar la precisión de los datos, aumentar la productividad y obtener información en tiempo real sobre las operaciones. Esto conduce a una mejor toma de decisiones y eficiencia general."
                ],
                [
                    "question" => "¿Cuáles son las características clave de un sistema ERP?",
                    "answer" => "Las características clave de un sistema ERP incluyen gestión financiera, gestión de la cadena de suministro, gestión de inventario, recursos humanos, gestión de relaciones con clientes (CRM) e informes/análisis."
                ],
                [
                    "question" => "¿Cómo mejora un sistema ERP los procesos empresariales?",
                    "answer" => "Un sistema ERP mejora los procesos empresariales automatizando tareas repetitivas, reduciendo la entrada manual de datos, garantizando la consistencia de los datos y proporcionando una vista unificada de las operaciones comerciales, lo que ayuda a tomar decisiones informadas."
                ],
                [
                    "question" => "¿Qué tipos de empresas pueden beneficiarse de un sistema ERP?",
                    "answer" => "Casi cualquier tipo de empresa, independientemente de su tamaño o industria, puede beneficiarse de un sistema ERP. Esto incluye manufactura, retail, cuidado de la salud, finanzas, educación y más."
                ],
                [
                    "question" => "¿Cuáles son las diferencias entre los sistemas ERP locales y basados en la nube?",
                    "answer" => "Los sistemas ERP locales se instalan localmente en los servidores de la empresa, mientras que los sistemas ERP basados en la nube se alojan en los servidores del proveedor y se accede a ellos a través de internet. Los ERP basados en la nube a menudo ofrecen ventajas en términos de escalabilidad, costos y accesibilidad."
                ],
                [
                    "question" => "¿Cuánto tiempo lleva implementar un sistema ERP?",
                    "answer" => "El tiempo para implementar un sistema ERP varía ampliamente según la complejidad de los procesos comerciales, el tamaño de la organización y el nivel de personalización requerido. Puede variar desde unos pocos meses hasta más de un año."
                ],
                [
                    "question" => "¿Cuáles son los desafíos potenciales de implementar un sistema ERP?",
                    "answer" => "Los desafíos potenciales incluyen costos iniciales elevados, resistencia al cambio por parte de los empleados, problemas de migración de datos, complejidades de personalización del sistema y la necesidad de capacitación y soporte exhaustivos."
                ],
                [
                    "question" => "¿Cómo puede mejorar un sistema ERP la seguridad de los datos?",
                    "answer" => "Un sistema ERP mejora la seguridad de los datos centralizando la gestión de datos, proporcionando controles de acceso de usuario, cifrando información sensible y asegurando el cumplimiento de regulaciones y estándares de la industria."
                ],
                [
                    "question" => "¿Cómo apoya un sistema ERP la toma de decisiones?",
                    "answer" => "Un sistema ERP apoya la toma de decisiones proporcionando datos y análisis en tiempo real, generando informes completos y ofreciendo ideas predictivas. Esto ayuda a los gerentes y ejecutivos a tomar decisiones informadas y basadas en datos."
                ]
            ],
        ];

        $langList = ["en"=>"en", "es"=>"es"];
        unset($langList[$lang]);
        return view('erp', compact("faqs", "lang", "langList"));
    }

    public function store(StoreRequest $request){
        try {
            Mail::to(env("MAIL_FROM_ADDRESS"))->send(new ContactMailable($request->all()));
        } catch (\Exception $e) {
            return toast("Message not send", "error");
        }

        toast("Message send", "success");
        return route("landing.index") . '#contact';
    }
}
