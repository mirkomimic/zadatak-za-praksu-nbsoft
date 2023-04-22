const form = document.getElementById("form");
const ime = document.getElementById("ime");
const prezime = document.getElementById("prezime");
const godinaRodjenja = document.getElementById("godinaRodjenja");
const grad = document.getElementById("grad");
const adresa = document.getElementById("adresa");
const polInputs = document.getElementsByClassName("form-check-input");
const jezikCheck = document.getElementsByClassName("form-check-input2");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  // pol
  var pol;

  for (var i = 0; i < polInputs.length; i++) {
    if (polInputs[i].checked === true) {
      pol = polInputs[i];
    }
  }
  //-------------------------------
  // programski jezici
  const jezikCheckLenght = jezikCheck.length;
  const jezici = [];
  var jeziciString = "";
  for (var i = 0; i < jezikCheck.length; i++) {
    if (jezikCheck[i].checked === true) {
      jezici.push(jezikCheck[i].defaultValue);
      jeziciString += jezikCheck[i].defaultValue + ", ";
    }
  }
  jeziciString = jeziciString.substring(0, jeziciString.length - 2);

  // ----------------------

  let error = false;

  if (ime.value === "") {
    document.getElementById("imeError").innerHTML = "Ime je obavezno polje.";
    hasError = true;
    return;
  } else {
    document.getElementById("imeError").innerHTML = "";
  }
  if (prezime.value === "") {
    document.getElementById("prezimeError").innerHTML = "Prezime je obavezno polje.";
    hasError = true;
    return;
  } else {
    document.getElementById("prezimeError").innerHTML = "";
  }

  if (checkMale.checked == false && checkFemale.checked === false) {
    document.getElementById("polError").innerHTML = "Izaberite pol.";
    hasError = true;
    return;
  } else {
    document.getElementById("polError").innerHTML = "";
  }

  if (godinaRodjenja.value === "") {
    document.getElementById("godRodjenjaError").innerHTML = "Izaberite godinu rodjenja.";
    hasError = true;
    return;
  } else {
    document.getElementById("godRodjenjaError").innerHTML = "";
  }
  if (grad.value === "") {
    document.getElementById("gradError").innerHTML = "Izaberite grad.";
    hasError = true;
    return;
  } else {
    document.getElementById("gradError").innerHTML = "";
  }
  if (adresa.value === "") {
    document.getElementById("adresaError").innerHTML = "Adresa je obavezno polje.";
    hasError = true;
    return;
  } else {
    document.getElementById("adresaError").innerHTML = "";
  }
  if (jezici.length < 1) {
    document.getElementById("jezikError").innerHTML = "Izaberite najmanje jedan programski jezik.";
    hasError = true;
    return;
  } else {
    document.getElementById("jezikError").innerHTML = "";
  }

  if (error == false) {
    request = $.ajax({});
    request.done(function (response, status, jqXHR) {
      $("#form").hide();
      var msg = `
      <p class="text-success">Podaci su prosledjeni!</p>
      <p class="fw-bold">Prosledjene vrednosti:</p>
      <p>Ime: ${ime.value}</p>
      <p>Prezime: ${prezime.value}</p>
      <p>Pol: ${pol.defaultValue}</p>
      <p>Godina rodjenja: ${godinaRodjenja.value}</p>
      <p>Grad: ${grad.value}</p>
      <p>Adresa: ${adresa.value}</p>
      <p>Programski jezik: ${jeziciString}</p>
      `;
      $("#form-success").append(msg);
    });
    request.fail(function (jqXHR, textStatus, error) {
      console.log("Error  " + textStatus, error);
    });
  }
});
