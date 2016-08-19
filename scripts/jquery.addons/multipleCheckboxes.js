// JavaScript Document
function rtrim (str, charlist) {
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: rtrim('    Kevin van Zonneveld    ');
  // *     returns 1: '    Kevin van Zonneveld'
  charlist = !charlist ? ' \\s\u00A0' : (charlist + '').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
  var re = new RegExp('[' + charlist + ']+$', 'g');
  return (str + '').replace(re, '');
}


function http_build_query (formdata, numeric_prefix, arg_separator) {
  // %        note 1: If the value is null, key and value is skipped in http_build_query of PHP. But, phpjs is not.
  // -    depends on: urlencode
  // *     example 1: http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;');
  // *     returns 1: 'foo=bar&amp;php=hypertext+processor&amp;baz=boom&amp;cow=milk'
  // *     example 2: http_build_query({'php': 'hypertext processor', 0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'cow': 'milk'}, 'myvar_');
  // *     returns 2: 'php=hypertext+processor&myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&cow=milk'
  var value, key, tmp = [],
    that = this;

  var _http_build_query_helper = function (key, val, arg_separator) {
    var k, tmp = [];
    if (val === true) {
      val = "1";
    } else if (val === false) {
      val = "0";
    }
    if (val != null) {
      if(typeof(val) === "object") {
        for (k in val) {
          if (val[k] != null) {
            tmp.push(_http_build_query_helper(key + "[" + k + "]", val[k], arg_separator));
          }
        }
        return tmp.join(arg_separator);
      } else if (typeof(val) !== "function") {
        return that.urlencode(key) + "=" + that.urlencode(val);
      } else {
        throw new Error('There was an error processing for http_build_query().');
      }
    } else {
      return '';
    }
  };

  if (!arg_separator) {
    arg_separator = "&";
  }
  for (key in formdata) {
    value = formdata[key];
    if (numeric_prefix && !isNaN(key)) {
      key = String(numeric_prefix) + key;
    }
    var query=_http_build_query_helper(key, value, arg_separator);
    if(query !== '') {
      tmp.push(query);
    }
  }

  return tmp.join(arg_separator);
}


$(document).ready(function() 
{
        //uncheck all boxes initially on pageload
        $('.chkItem').attr('checked', false);

        //get the initial link for each link and insert it into an array
        var iniLink = [];
        var elementcount = 0;
        
         $('.checkItem').each(
                 function(){
                    iniLink[elementcount] = $(this).attr('href');                    
                    elementcount++;
                 }
            );

        //function for getting the url variables from Link
         function getUrlVars(tbLink)
         {
                var vars = {}, hash;
                var q = tbLink.split('?')[1];
                if(q != undefined){
                    q = q.split('&');
                    for(var i = 0; i < q.length; i++){
                        hash = q[i].split('=');
                        //vars.push(hash[1]);
                        vars[hash[0]] = hash[1];
                    }
            }
            
            return vars;
         }

         //function for processing the link
           $('.chkItem').click(function () 
                {         
                         var chkValue = $(this).val();
                         var id;

                         if(chkValue != undefined)
                         {
                                var allVals = chkValue;
                                id = getUrlVars($('.checkItem').attr('href'));

                                if(id['ids'] == undefined)
                                {
                                        //this adds the first value
                                        //$('.checkItem').attr('href',tbLink+'&ids='+allVals);
                                        //check the url, set the link
                                        $('.checkItem').each(
                                            function (){
                                                 var tbLink = $(this).attr('href');
                                                 $(this).attr('href',tbLink+'&ids='+allVals);                                              
                                                 //alert(tbLink);
                                            } 
                                        );
                                        
                                }
                                else
                                {
                                        //this adds the remaining values
                                        //split the URL
                                        var idarray = id['ids'].split(',');

                                        if(jQuery.inArray(chkValue,idarray)=='-1')
                                        {
                                                //only adds new values only
                                                //$('.checkItem').attr('href',tbLink+','+allVals);
                                                $('.checkItem').each(
                                                function (){
                                                        var tbLink = $(this).attr('href');

                                                        $(this).attr('href',tbLink+','+allVals);
                                                        id = getUrlVars(tbLink);
                                                        //alert(tbLink);
                                                   } 
                                               );
                                        }
                                        else
                                        {
                                                //removes unchecked values
                                                var idPos = jQuery.inArray(chkValue,idarray);
                                                idarray.splice(idPos,1);

                                                //$('.checkItem').attr('href',iniLink+'&ids='+idarray);
                                                $('.checkItem').each(
                                                function (){
                                                     var tbLink = $(this).attr('href');
                                                     var url = getUrlVars(tbLink); 
                                                     
                                                     //remove the ids value
                                                     //url.splice($.inArray('ids',url),1);
                                                     //url = url.not('ids');
                                                     //delete url['ids'];
                                                     
                                                     /*
                                                     $.each(url, function(key, value) {
    alert( "The key is '" + key + "' and the value is '" + value + "'" );
});
                                                    */
                                                   var param;
                                                   
                                                     $.each(url, function(k, v) {
     if((encodeURIComponent(k)!='ids')&&(encodeURIComponent(k)!=='undefined')&&(encodeURIComponent(v)!=='undefined'))
    {
        param += encodeURIComponent(k) + '=' + encodeURIComponent(v)+'&';
    }
});
param = rtrim(param,'&');
param = param.replace('undefinedadmin','admin');  
                                                    
                                                    $(this).attr('href','?'+param+'&ids='+idarray);
                                                } 
                                            );
                                        }

                                }
                         }
                }						   
           );	

        //check all boxes
         $(function () {
                $('.checkall').toggle(
                        function() {
                                $('.chkItem').attr('checked', true);
                                var chkval = $('.chkItem').val();
                                var chkcount = $('.chkItem').length;

                                var ids = new Array();

                                $('.chkItem').each(function(){

                                        ids[this.id] = $(this).val();
                                        //alert($(this).val());
                                });

                                //join the array with commas
                                var idlist = ids.join(',');
                                //alert(idlist);

                                //$('.checkItem').attr('href',tbLink+'&ids='+idlist);
                                $('.checkItem').each(
                                                function (){
                                                     var tbLink = $(this).attr('href');

                                                     $(this).attr('href',tbLink+'&ids='+idlist);
                                                     //id = getUrlVars(tbLink);
                                                     //alert(tbLink);
                                                } 
                                            );
                        },
                        function() {
                                $('.chkItem').attr('checked', false);

                                $('.checkItem').each(
                                    function (){
                                                     var tbLink = $(this).attr('href');
//alert(tbLink);
                                                     var url = getUrlVars(tbLink); 
                                                     var param;
                                                     //remove the ids value
                                                     //url.splice($.inArray('ids',url),1);
                                                     /*
                                                     $.each(url, function(key, value) {
    alert( "The key is '" + key + "' and the value is '" + value + "'" );
});
                                                     */
                                                     $.each(url, function(v, k) {
    //alert(encodeURIComponent(v));
    if((encodeURIComponent(v)!='ids')&&(encodeURIComponent(k)!=='undefined')&&(encodeURIComponent(v)!=='undefined'))
    {
        param += encodeURIComponent(v) + '=' + encodeURIComponent(k)+'&';
    }
});
param = rtrim(param,'&');
param = param.replace('undefinedadmin','admin');                                                    
                                                     $(this).attr('href','?'+param);                                                     
                                                }
                                );
                        }
                );
        });

});