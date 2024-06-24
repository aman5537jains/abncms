<?php
namespace Aman5537jains\AbnCms\Editor\Plugins;

use Aman5537jains\AbnCms\Editor\Plugin;

class LoanCalcultor extends Plugin{





    // function category()
    // {
    //     return 'Calculator';
    // }

    function content()
    {
        return view("front.pages.home-elements.loan")->render();
    }
    function defaults(){
        return [

            "attributes"=>[
                "type"=> 'number',
                "name"=>"default"
            ],
            "traits"=>[
                "name",
                "type"
            ]
        ];
    }

    function customScript(){
         return true;
    }


    function scripts(){
        $fn = $this->scriptFunctionName();

        return [


                "script"=>[
                    "src"=>"http://localhost/wjf_web/public/loan.js"
                    // "text"=>"
                    //     function $fn(){

                    //          return {
                    //             model: {
                    //                 defaults: {
                    //                     traits:[ 'name' ]
                    //                 }
                    //             }

                    //          }
                    //     }
                    //  "
                ]

        ];
    }








}


class LoanCalcultorType{

}
