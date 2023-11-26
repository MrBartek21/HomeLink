window.onload = general;
function general(){
	setTimeout("zegar()",1);
}

function leadingZero(i){
    return (i < 10)? "0"+i : i;
}

function zegar(){
	var now = new Date();
	const days = ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota"];
	const monthNames = ["styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień"];
	
	var dzien = now.getDay();
	var miesiac = now.getMonth()+1;
	var rok = now.getFullYear();


	var sekunda = now.getSeconds();
	var minuta = now.getMinutes();
	var godzina = now.getHours();


	godzina = leadingZero(godzina);
	minuta = leadingZero(minuta);
	sekunda = leadingZero(sekunda);

	
	document.getElementById("infoDiv").innerHTML = days[dzien]+", "+monthNames[miesiac]+" "+dzien+","+rok+" "+godzina+":"+minuta+":"+sekunda;
	
	setTimeout("zegar()",1000);
}