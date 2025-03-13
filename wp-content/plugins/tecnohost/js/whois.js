function whois_domains(whois_domain, options_service_input, mode_domain){
    console.log(whois_domain);
                        $.ajax({
                            type:"POST", 
                            url:"/wp-json/whois/v1/domain",
                            data:{whois_domain: whois_domain,options_service_input: options_service_input, mode_domain: mode_domain  },
                            beforeSend: function(){ 
                                 $("#container-ajax-domains").html("<div class=\"button loading plan-hosting-loader\"></div><br><p style='text-align: center;'>Por favor, espere...</p>");
                             },
                            success: function(datos){ 
                                  $("#container-ajax-domains").html(datos);
                             },
                        });
                    }
                    
