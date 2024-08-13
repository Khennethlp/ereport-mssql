// const getData_masterlist = () => {

//     $.ajax({
//         type: "method",
//         url: "../../process/superAdmin/load_data.php",
//         data: {
//             method: 'load_dataTable'
//         },
//         success: function (response) {
//             document.getElementById('m_report_table').innerHTML = response;
//         }
//     });
// }

// document.addEventListener("DOMCONTENTLOADED", function(){
// });

const getData_masterlist = async () => {
    const sortBy = document.getElementById("sortBy");
    const getSerial = sessionStorage.getItem("setSerialNo");

    try {
        const response = await fetch("../../process/superAdmin/load_data.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                sortBy: sortBy.value,
                serialNo: getSerial,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json(); // Parse the JSON response

        // Update the HTML content of the table
        document.getElementById("m_report_table").innerHTML = data.html;

        // Update the batch number, group number, and training group fields
        if (data.data.error) {
            console.error(data.data.error);
            alert(data.data.error);
        } else {
            document.getElementById("batchNo").value = data.data.batch_no || '';
            document.getElementById("groupNo").value = data.data.group_no || '';
            document.getElementById("training_group").value = data.data.training_group || '';
        }

        // Get the serial number links and set up event listeners
        const viewLinks = document.querySelectorAll("a[data-serial]");
        viewLinks.forEach(function (link) {
            link.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default action (e.g., following the link)

                const serialNumber = this.getAttribute("data-serial"); // Get the data-serial attribute value

                document.getElementById("serialNo").value = serialNumber;
                console.log("Serial Number:", serialNumber); // Log it to the console
                sessionStorage.setItem("setSerialNo", serialNumber); // Set to session storage
            });
        });

    } catch (error) {
        console.error("Error fetching data:", error);
    }
};
// const get_training_record = async () => {
//     var getSerial = sessionStorage.getItem('setSerialNo');

//     console.log('Serial Number from Session Storage:', getSerial);
//     try {
//         const response = await fetch('../../process/superAdmin/load_data.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: new URLSearchParams({
//                 // method: 'load_dataTable',
//                 serialNo: getSerial,
//             })
//         });

//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status}`);
//         }

//         const data = await response.json();
//         document.getElementById('batchNo').value = data.batch_no;
//         document.getElementById('groupNo').value = data.group_no;
//         document.getElementById('training_group').value = data.training_group;

//     } catch (error) {
//         console.error('Error fetching data:', error);
//     }
// }

document.getElementById("sortBy").addEventListener("change", () => {
  getData_masterlist(); // Fetch the data when sortBy changes
});

getData_masterlist();
