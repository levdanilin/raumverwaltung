function updateDataFloorBuilding(event) {
  const targetUrl = `http://localhost/bfw/Raumverwaltung-neu/controller/LEVUpdateFloorBuildingCommand.php?rid=${event.target.value}`;

  fetch(targetUrl, {
    method: 'GET'
  })
  .then(response => response.json())
  .then(result => {
    const building = document.querySelector("#building");
    const floor = document.querySelector("#floor");

    building.innerHTML = result['result']['building'];
    floor.innerHTML = result['result']['floor'];

    handleLookupResults();
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function toggleDetails(event) {
  event.preventDefault();

  const floorBuilding = document.querySelector("#bookingPossibilities");
  const roomNumbers = document.querySelector("#roomNum");
  const toggleDetailsButton = document.querySelector("#toggleDetailsButton");

  if (getComputedStyle(floorBuilding).display === 'flex' && getComputedStyle(roomNumbers).display === 'block') {
    floorBuilding.style.display = "none";
    roomNumbers.style.display = "none";
    toggleDetailsButton.innerText = "Details einblenden";
  } else {
    floorBuilding.style.display = "flex";
    roomNumbers.style.display = "block";
    toggleDetailsButton.innerText = "Details ausblenden";
  }
}

function showAllAvailables(bookings, startDate, endDate, selectedRid) {
  const targetUrl = `http://localhost/bfw/Raumverwaltung-neu/controller/LEVavailablesCommand.php`;
  const availableResultsData = document.querySelector("#bookingResultData");
  const retrievedObject = JSON.parse(localStorage.getItem('userObject'));

  if (!startDate) {
    startDate = new Date().toISOString().split('T')[0];
  }

  if (!endDate) {
    endDate = startDate;
  }

  const dateRange = function(s, e) {
    const a = new Array();

    for (let i = new Date(s); i <= new Date(e); i.setDate(i.getDate() + 1)) {
      const k = new Date(i).toISOString().split('T')[0];
      a.push(k);
    }

    return a;
  };

  const dateRangeArray = dateRange(startDate, endDate);

  fetch(targetUrl, {
    method: 'GET'
  })
  .then(response => response.json())
  .then(result => {
    console.log(result);
    const roomIds = Object.keys(result['allAvailables']);
    let uniqueBookingDates = new Array();
    let response = '';

    bookings.forEach(b => {
      if (!uniqueBookingDates.includes(b['date'])) {
        uniqueBookingDates.push(b['date']);
      }
    });

    uniqueBookingDates.sort(function(a, b) {
      let d1 = new Date(a);
      let d2 = new Date(b);

      if (d1 < d2) {
        return -1;
      } else if (d1 == d2) {
        return 0;
      } else {
        return 1;
      }
    });

    dateRangeArray.forEach(u => {
      response += `<table><tr><th class="table-header">Datum</th><th class="table-header">Raumnummer</th><th class="table-header">${result['allAvailables'][roomIds[0]][0]['period']}</th><th class="table-header">${result['allAvailables'][roomIds[0]][1]['period']}</th><th class="table-header">${result['allAvailables'][roomIds[0]][2]['period']}</th><th class="table-header">${result['allAvailables'][roomIds[0]][3]['period']}</th></tr>`;

      roomIds.forEach(res => {
        if (res !== selectedRid && selectedRid !== '') {
          return;
        }

        const indexOfRnumbers = result['roomNumbersAndIds'].findIndex(k => k['rid'] === res);
        const startDateElement = document.querySelector("#startDate");
        const rnumber = result['roomNumbersAndIds'][indexOfRnumbers]['number'];
        response += `<tr class="table-row"><td class="table-data">${u}</td><td class="table-data">${rnumber}</td>`;

        result['allAvailables'][res].forEach(r => {
          const indexOfBookings = bookings.findIndex(k => k['date'] === u && k['rid'] === parseInt(res) && String(k['tid']) === r['tid']);
          const period = r['period'];

          if (indexOfBookings >= 0 && r['tid'] === String(bookings[indexOfBookings]['tid'])) {
            response += `<td class="table-data text-color-dark">gebucht</td>`;
          } else {
            const genId = generateId();

            if (result['role'] === "gast") {
              response += `<td id="${genId}" class="table-data text-color-green">Frei</td>`;
            } else {
              response += `<td id="${genId}" class="table-data text-link" onclick="handleLookupBooking('${res}', '${u}', '${r['tid']}', '${retrievedObject['uid']}', '${genId}', '${rnumber}', '${period}');">Frei</td>`;
            }

          }
        });

        response += '</tr>';
      });

      response += `</table><br><br>`;
    });

    availableResultsData.innerHTML = response;
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function generateId() {
  let characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  let randstring = '';

  for (j = 0; j < 10; j++) {
    randstring += characters[Math.floor(Math.random() * characters.length)];
  }

  return randstring;
}

function handlePrintBooking(date, rnumber, period, name, secondname) {
  let divElement = document.querySelector('#mainContent');
  let confirmation = "";

  confirmation += "<h1>Buchnungsbestätigung:</h1>";
  confirmation += `<span class="text-color-blue">Name:</span> ${name} <span class="text-color-blue">Nachname:</span> ${secondname}<br><br>`;
  confirmation += `<span class="text-color-blue">Raum:</span> ${rnumber} <span class="text-color-blue">Datum:</span> ${date} <span class="text-color-blue">Zeit:</span> ${period}<br><br>`;
  confirmation += `<button onclick="window.print();" class="button text-xl">Drucken</button>`;

  divElement.innerHTML = confirmation;
}

function handleLookupBooking(rid, date, tid, uid, tdid, rnumber, period) {
  const yesorno = confirm(`Wollen Sie den Raum '${rnumber}' am '${date}' für '${period}' buchen?`);
  const urlParams = `?rid=${rid}&date=${date}&tid=${tid}&uid=${uid}`;
  const targetUrl = `http://localhost/bfw/Raumverwaltung-neu/controller/LEVhandleLookupBookingCommand.php${urlParams}`;

  if(yesorno === true) {
    fetch(targetUrl, {
      method: 'GET'
    })
    .then(response => response.json())
    .then(result => {
      if (result['booking'].hasOwnProperty('status') && result['booking']['status'] === 'success') {
        const td = document.querySelector(`#${tdid}`);
        td.innerHTML = "gebucht";
        handlePrintBooking(date, rnumber, period, result['user']['name'], result['user']['secondname']);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
}

function handleLookupResults() {
  const startDateElement = document.querySelector("#startDate");
  const endDateElement = document.querySelector("#endDate");
  const roomIdElement = document.querySelector("#roomNumbers");
  const floorBuilding = document.querySelector("#bookingPossibilities");

  const startDate = startDateElement.value;
  const endDate = endDateElement.value;

  let rid = '';

  if (getComputedStyle(floorBuilding).display === 'flex') {
    rid = roomIdElement.value;
  } else {
    rid = '';
  }

  const urlParams = `?startDate=${startDate}&endDate=${endDate}&rid=${rid}`;
  const targetUrl = `http://localhost/bfw/Raumverwaltung-neu/controller/LEVhandleLookupResultsCommand.php${urlParams}`;

  fetch(targetUrl, {
    method: 'GET'
  })
  .then(response => response.json())
  .then(result => {
    showAllAvailables(result, startDate, endDate, rid);
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function getFormBookingPossibilities(id) {
  const urlParams = new URLSearchParams(window.location.search);
  const idParam = urlParams.get('id');

  const myHeaders = new Headers({
    'Content-Type': 'text/html',
  });

  const targetUrl = `http://localhost/bfw/Raumverwaltung-neu/template/LEVcheckBookingPossibilities.php?id=${idParam}`;
  const targetUrlRoomNumbers = `http://localhost/bfw/Raumverwaltung-neu/controller/LEVcheckBookingPossibilitiesCommand.php?id=${idParam}`;
  const divElement = document.querySelector(`#${id}`);
  const homepageLink = document.querySelector(`#homepageLink`);
  let optionElements = new Array();

  fetch(targetUrl, {
    method: 'GET',
    headers: myHeaders
  })
  .then(response => response.text())
  .then(result => {
    divElement.innerHTML = result;

    fetch(targetUrlRoomNumbers, {
      method: 'GET'
    })
    .then(response => response.json())
    .then(result => {
      if (result['role'] === "administrator") {
        homepageLink.style.display = "block";
      }

      let elementOption = '';

      result['roomObjects'].forEach(res => {
        if (result['room']['rnumber'] === res['rnumber']) {
          elementOption += `<option value="${res['rid']}" selected>${res['rnumber']}</option>`;
        } else {
          elementOption += `<option value="${res['rid']}">${res['rnumber']}</option>`;
        }
      });

      const roomNumbers = divElement.querySelector("#roomNumbers");
      const building = divElement.querySelector("#building");
      const floor = divElement.querySelector("#floor");
      const startDate = document.querySelector("#startDate");
      const endDate = document.querySelector("#endDate");

      building.innerHTML = result['room']['building'];
      floor.innerHTML = result['room']['floor'];

      roomNumbers.innerHTML = elementOption;

      roomNumbers.addEventListener('change', updateDataFloorBuilding);
      startDate.addEventListener('change', handleLookupResults);
      endDate.addEventListener('change', handleLookupResults);
    })
    .catch(error => {
      console.error('Error:', error);
    });
  })
  .catch(error => {
    console.error('Error:', error);
  });
}
