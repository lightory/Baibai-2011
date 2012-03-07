function loadDisctrict(id, postcode){
  var content;
  for (var key in data){
    if(data[key][1] == postcode){
      content += "<option value=\"" +  key + "\" >" + data[key][0] + "</option>";
    }
  }
  
  var $target;
  if(id == "province"){
    $target = $("#province");
  } else if(id == "city"){
    $target = $("#city");
  } else if(id == "district"){
    $target = $("#district");
  }
  
  $target.empty().append(content);
}

var refreshCity = function(){
  var postcode = $("#province").val();
  loadDisctrict("city", postcode);
};

var refreshDistrict = function(){
  var postcode = $("#city").val();
  loadDisctrict("district", postcode);
};

loadDisctrict("province", "1");
refreshCity();
refreshDistrict();

$("#province").change(function(){
  refreshCity();
  refreshDistrict();
});
$("#city").change(function(){
  refreshDistrict();
});

// before Submit
$('form.profile').submit(function(){
  var postcode = $('#district').val();
  if(postcode){
    var district = data[postcode][0];
    
    postcode = data[postcode][1];
    var city = data[postcode][0];
    
    postcode = data[postcode][1];
    var province = data[postcode][0];
  } else{
    postcode = $('#city').val();
    var city = data[postcode][0];
    
    postcode = data[postcode][1];
    var province = data[postcode][0];
  }
  
  $('#district2').val(district);
  $('#city2').val(city);
  $('#province2').val(province);
  
  return true;
});