
var r = $('#select_box option:selected').val()
console.log(r);

window.onload = function(){
    var select = document.querySelector("#select_box");
    select.addEventListener('change', function(){
    document.select_form.submit();
    },);
}