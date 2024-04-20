function checkear(obj) {
  hijos = obj.children;
  for (var i = 0; i < hijos.length; i++) {
    if (hijos[i].type == "checkbox") {
      if (hijos[i].checked == true) {
        hijos[i].checked = false;
      } else {
        hijos[i].checked = true;
      }
    }
  }
}

/* const getApi = (url) => {
  return fetch(url, {
    method: "GET",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    credentials: "include",
  });
};

const postApi = async (url, data = {}) => {
  return fetch(url, {
    method: "POST",
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
      Accept: "application/json",
      //"Content-Type": "application/json",
    },
    credentials: "include",
    body: "data=" + JSON.stringify(data),
  });
}; */

/**
 * Funcion para mostrar u ocultar la pantalla de transwicion
 * @param {boolean} activar Para activar o desactivar la pantallas de transicion
 * @param {String} titulo  para ingresar un titulo de la pantalla de transicion
 * @param {String} mensaje Para describir un poco mas la pantallas de transiciion
 */
const spinner = (
  activar = false,
  titulo = "Espere por favor",
  mensaje = "El sistema esta realizando tareas de comprobación aguarde un momento por favor.."
) => {
  document.querySelector(".transicion .titulo").innerHTML = titulo;
  document.querySelector(".transicion .mensaje").innerHTML = mensaje;
  if (activar) {
    document.querySelector(".transicion").style.display = "grid";
  } else {
    document.querySelector(".transicion").style.display = "none";
  }
};

$(document).ready(() => {
  spinner(false);
});
