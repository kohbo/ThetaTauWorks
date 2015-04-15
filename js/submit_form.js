function validateform(form){
	if(form['company'].value == "" &&
		form['position'].value == "" &&
		form['contact'].value == ""){
		alert("Company, Position, and Contact can't be blank. At least one is required.");
		
		return false;
	} else {
		form['contact'].value = (form['contact'].value).replace("http://","");
		form['contact'].value = (form['contact'].value).replace("https://","");
		form['information'].value = (form['information'].value).replace("http://","");
		form['information'].value = (form['information'].value).replace("https://","");
		// Finally submit the form. 
		form.submit();
    	return true;
	}
}