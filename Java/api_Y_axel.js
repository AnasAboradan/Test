
//* initialize drop down list */ 

function init_drop_down_list()
{
var request = new XMLHttpRequest();
    request.onreadystatechange = (e) => {
    if (request.readyState != 4) {  
    return;   //Error
  }
  
  if (request.status === 200) {   // success
     var data=JSON.parse( request.responseText);
     if(!data['success']) alert(data['message']); //Error
     else  Add_years_to_dropDownList(data['data']);
  } 
  };
  
  request.open('GET','http://localhost/test/PHP/API.php?list_of_year=""');
  request.send();
}


// for each year create <a> item and add it to drop down list

function Add_years_to_dropDownList(years)
{
    let drop_down_list=document.getElementById('items');
    drop_down_list.innerHTML="";
    let length=years.length;

    for (let i = 0; i < length; i++)
    {
      const A_tag = document.createElement("a");
      A_tag.href="#";
      A_tag.innerText=years[i];
      A_tag.addEventListener('click', function(){
        get_associated_data(years[i]);
      });
      drop_down_list.appendChild(A_tag);
    } 
}


// for a unique yeas get its associated data (county code, number of males/females)
function get_associated_data(year)
{
var request = new XMLHttpRequest();
    request.onreadystatechange = (e) => {
    if (request.readyState != 4) {  
    return;   //Error
  }
  
  if (request.status === 200) {   // success
     var data=JSON.parse( request.responseText);
     if(!data['success']) alert(data['message']); //Error
     else create__Y_Axxel(data);                  // send the data  
    //console.log(request.responseText);

  } 
  };
  
  request.open('GET', 'http://localhost/test/PHP/API.php?year='+year);
  request.send();
}

function create__Y_Axxel(data)
{
    let Y_axel=document.getElementById('Y');        //get y axel element
   
    Y_axel.innerHTML=""                             // refresh y axel
   
    var keys = Object.keys(data['data']);           // for each country code create one line for males and one for females
    keys.forEach(function(key){
    
    let m_height=calculate_line_height(data["data"][key]["males"]); //scale the geight
    let f_height=calculate_line_height(data["data"][key]["females"]);
   
    Y_axel.innerHTML+=      '<li class="bar">'+                  // Add new  bar to Y axel  // 
                               '<div class="lines">'+
                                  '<div style="height:'+m_height+'vh;" class="males"></div>'+
                                  '<div style="height:'+f_height+'vh;" class="femels"></div>'+
                                '</div>'+
                                '<div class="code">'+
                                  '<span>'+key+'</span>'+
                                '</div>'+
                            '</li>';
    });
}


function calculate_line_height(value)
{
    if(value==null)
      return 0;
    
    let scale=0.101; // every one person is 0.101 vh
    let height=parseFloat(value) *scale;
    return height;  

}


  ////* main *////

  init_drop_down_list();