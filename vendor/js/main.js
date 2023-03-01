"use strict";

function pagination(totalPages, currentPage) {
  if (totalPages > 1) {
    var pageList = "";
    currentPage = parseInt(currentPage);

    pageList += `<ul class="pagination justify-content-center">`;

    const disabledClass = currentPage == 1 ? " disabled" : "";

    pageList += `
    <li class="page-item ${disabledClass}">
      <a class="page-link" data-page="${currentPage - 1}">Previous</a>
    </li>`;

    for (let page = 1; page <= totalPages; page++) {
      const activeClass = currentPage == page ? " active" : "";
      pageList += `
      <li class="page-item ${activeClass}">
         <a class="page-link" href="#" data-page="${page}">
           ${page}
         </a>
       </li>`;
    }

    const disabledNextClass = currentPage == totalPages ? " disabled" : "";

    pageList += `
    <li class="page-item ${disabledNextClass}">
      <a class="page-link" href="#"  data-page="${currentPage + 1}">
        Next
      </a>
    </li>`;

    pageList += `</ul>`;
  }
  $("#pagination").html(pageList);
}

function getPlayerRow(player) {
  var playerRow = "";

  if (player) {
    const playerPhoto = player.photo ? player.photo : "images.jpeg";
    playerRow = `

      <tr>
        <td>
        <img src="uploads/${playerPhoto}" class="img-thumbnail" alt="${player.pname}">
        </td>
        <td>${player.pname}</td>
        <td>${player.pemail}</td>
        <td>${player.phone}</td>
        <td>
            <div class="d-flexjustify-content-between">
                <button class="btn btn-success profile" data-bs-toggle="modal" data-bs-target="#afficherPlayer" data-id="${player.id}"><i class="fa fa-address-card"></i></button>
                <button class="btn btn-warning edituser" data-bs-toggle="modal" data-bs-target="#editerPlayer"  data-id="${player.id}"><i class="fa fa-edit"></i></button>
                <button class="btn btn-danger deluser"  data-id="${player.id}"><i class="fa fa-trash"></i></button>
            </div>
        </td>
    </tr>
    
    `;
  }

  return playerRow;
}

function getAllPlayers() {
  var page = $("#currentPage").val();

  $.ajax({
    type: "GET",
    url: "/ajaxPooPhp/ajax.php",
    data: { page: page, action: "getusers" },
    dataType: "json",
    beforeSend: function () {
      console.log("patientez...");
      $("#overlay").fadeIn();
    },
    complete: function () {
      $("#overlay").fadeOut();
      console.log("fin chargement");
    },
    success: function (response) {
      if (response.players) {
        var playerList = "";
        $.each(response.players, function (index, player) {
          playerList += getPlayerRow(player);
        });

        $("#playerTable tbody").html(playerList);
        let totalPlayers = response.count;
        let totalPages = Math.ceil(parseInt(totalPlayers) / 4);
        const currentPage = $("#currentPage").val();
        pagination(totalPages, currentPage);
      }
      console.log(response.count);
      console.log(response);
    },
  });
}

$(document).ready(function () {
  //   $("#overlay").fadeIn().delay(2000).fadeOut();

  $("#addform").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "/ajaxPooPhp/ajax.php",
      data: new FormData(this),
      dataType: "json",
      processData: false,
      contentType: false,
      beforeSend: function () {
        console.log("patientez...");
        $("#overlay").fadeIn();
      },
      complete: function () {
        $("#overlay").fadeOut();
        console.log("fin chargement");
      },
      success: function (response) {
        if (response.status === 200) {
          getAllPlayers();
          $(".modal-backdrop").remove();
          $("#ajouterPlayer").modal("hide");
          $("#addform")[0].reset();
        } else {
        }
        console.log(response);
      },
      error: function () {
        console.log("Erreur");
      },
    });
  });

  $(document).on("click", "ul.pagination li a", function (e) {
    e.preventDefault();
    const pageNum = $(this).data("page");
    $("#currentPage").val(pageNum);
    getAllPlayers();
    $(this).parent().siblings().removeClass("active");
    $(this).parent().addClass("active");
  });

  $(document).on("click", "button.edituser", function () {
    var pid = $(this).data("id");
    $.ajax({
      type: "GET",
      url: "/ajaxPooPhp/ajax.php",
      data: { page: pid, action: "edituser" },
      dataType: "json",
      beforeSend: function () {
        console.log("patientez...");
        $("#overlay").fadeIn();
      },
      complete: function () {
        $("#overlay").fadeOut();
        console.log("fin chargement");
      },
      success: function (response) {
        console.log(response);

        $("#nomEdit").val(response.pname);
        $("#emailEdit").val(response.pemail);
        $("#telEdit").val(response.phone);
        $("#userId").val(pid);
      },
    });
  });

  $("#editform").submit(function (e) {
    e.preventDefault();
    var v = $("#editform").serialize();
    $.ajax({
      type: "POST",
      url: "/ajaxPooPhp/ajax.php",
      data: new FormData(this),
      dataType: "json",
      processData: false,
      contentType: false,
      beforeSend: function () {
        console.log("patientez...");
        $("#overlay").fadeIn();
      },
      complete: function () {
        $("#overlay").fadeOut();
        console.log("fin chargement");
      },
      success: function (response) {
        if (response.status === 200) {
          getAllPlayers();
          $(".modal-backdrop").remove();
          $("#editerPlayer").modal("hide");
          $("#editform")[0].reset();
        } else {
          console.log(v);
        }
        console.log(response);
      },
      error: function () {
        console.log("Erreur");
      },
    });
  });

  $(document).on("click", "button.deluser", function () {
    var pid = $(this).data("id");
    $.ajax({
      type: "GET",
      url: "/ajaxPooPhp/ajax.php",
      data: { page: pid, action: "delUser" },
      dataType: "json",
      beforeSend: function () {
        console.log("patientez...");
        $("#overlay").fadeIn();
      },
      complete: function () {
        $("#overlay").fadeOut();
        console.log("fin chargement");
      },
      success: function (response) {
        getAllPlayers();
      },
    });
  });

  $(document).on("click", "button.profile", function () {
    var pid = $(this).data("id");
    $.ajax({
      type: "GET",
      url: "/ajaxPooPhp/ajax.php",
      data: { page: pid, action: "edituser" },
      dataType: "json",
      beforeSend: function () {
        console.log("patientez...");
        $("#afficherPlayerLabel").text("CHARGEMENT DES INFORMATIONS...");
        $("#userProfile").hide();
        $("button.btn-close").hide();
        $("button.btn-secondary").hide();
      },
      complete: function () {
        $("#afficherPlayerLabel").text('INFORMATIONS DU PLAYER');
        $("#userProfile").show("slow");
        $("button.btn-close").show("slow");
        $("button.btn-secondary").show("slow");
        console.log("fin chargement");
      },
      success: function (response) {
        console.log(response);

        const img = response.photo ? response.photo : 'images.jpeg';
        const profile = `
        <div class="row">

          <div class="col-md-4">
              <img src="uploads/${img}" alt="" class="img-thumbnail rounded responsive" style="height:150px !important; width:200px!important">
          </div>

          <div class="col-md-8">
              <div class="d-flex flex-column bd-highlight mb-3">
                  <h4 class="text-primary p-2 bd-highlight">${response.pname}</h4>
                  <p class="text-secondary p-2 bd-highlight">
                      <i class="fa fa-envelope"></i> ${response.pemail}
                      <br>
                      <i class="fa fa-phone"></i> ${response.phone}
                  </p>
              </div>
          </div>

        </div>`;
        $("#userProfile").html(profile);
      },
    });
  });

    

  //load players
  getAllPlayers();
});
