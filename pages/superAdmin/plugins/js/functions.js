
const get_details = (param) => {
    var data = param;

    var serial_no = data;
    console.log(param);

    $('#serialNo').val(serial_no);
    sessionStorage.setItem("setSerialNo", serial_no)
}

document.getElementById("sortBy").addEventListener("change", () => {
    getData_masterlist(); // Fetch the data when sortBy changes
  });

const getData_masterlist = () => {
//   var batchNo = document.getElementById("batchNo").value;
  var sortBy = document.getElementById("sortBy").value;
  const getSerial = sessionStorage.getItem("setSerialNo");

  $.ajax({
    type: "POST",
    url: "../../process/superAdmin/load_data.php",
    data: {
      method: "getData_masterlist",
      sortBy: sortBy,
      serial_no: getSerial
    },
    success: function (response) {
      document.getElementById("myDataTable").innerHTML = response;
    },
  });
};

// const data_modal = () => {
//   const getSerial = sessionStorage.getItem("setSerialNo");

//   $.ajax({
//     type: "POST",
//     url: "../../process/superAdmin/load_data.php",
//     data: {
//       method: "getData_masterlist",
//       serial_no: getSerial
//     },
//     success: function (response) {
//       document.getElementById("myDataTable").innerHTML = response;
//     },
//   });
// };

getData_masterlist();