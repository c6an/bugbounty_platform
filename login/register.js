function decide() {
  document.getElementById("decide").innerHTML =
    "<span style='color:blue;'>valid ID.&nbsp;</span>";
  document.getElementById("decide_id").value =
    document.getElementById("userid").value;
  document.getElementById("userid").disabled = true;
  document.getElementById("join_button").disabled = false;
  document.getElementById("check_button").value = "Change ID";
  document.getElementById("check_button").setAttribute("onclick", "change()");
}
function change() {
  document.getElementById("decide").innerHTML =
    "<span style='color:red;'>Please check ID duplication.&nbsp;</span>";
  document.getElementById("userid").disabled = false;
  document.getElementById("userid").value = "";
  document.getElementById("join_button").disabled = true;
  document.getElementById("check_button").value = "Check ID Duplication";
  document.getElementById("check_button").setAttribute("onclick", "checkId()");
}
function checkId() {
  var userid = document.getElementById("userid").value;
  if (userid) {
    url = "check.php?userid=" + userid;
    window.open(url, "chkid", "width=400,height=200");
  } else {
    alert("Please enter an ID.");
  }
}

const sendit = () => {
  const userid = document.regiform.userid;
  const userpw = document.regiform.userpw;
  const userpw_ch = document.regiform.userpw_ch;
  const username = document.regiform.username;
  const userphone = document.regiform.userphone;
  const useremail = document.regiform.useremail;

  // username값이 비어있으면 실행.
  if (username.value == "") {
    alert("Please enter your name.");
    username.focus();
    return false;
  }
  // 한글 이름 형식 정규식
  const expNameText = /[가-힣a-zA-Z0-9]+$/;
  // username값이 정규식에 부합한지 체크
  if (!expNameText.test(username.value)) {
    alert("Name format is incorrect.");
    username.focus();
    return false;
  }
  if (username.value.length > 10) {
    alert("Name must be 10 characters or less.");
    username.focus();
    return false;
  }
  if (userid.value == "") {
    alert("Please enter an ID.");
    userid.focus();
    return false;
  }
  // userid값이 4자 이상 12자 이하를 벗어나면 실행.
  if (userid.value.length < 3 || userid.value.length > 10) {
    alert("ID must be between 3 and 10 characters.");
    userid.focus();
    return false;
  }
  // userpw값이 비어있으면 실행.
  if (userpw.value == "") {
    alert("Please enter a password.");
    userpw.focus();
    return false;
  }
  // userpw_ch값이 비어있으면 실행.
  if (userpw_ch.value == "") {
    alert("Please enter a password confirmation.");
    userpw_ch.focus();
    return false;
  }
  // userpw값이 6자 이상 20자 이하를 벗어나면 실행.
  if (userpw.value.length < 3 || userpw.value.length > 20) {
    alert("Password must be between 3 and 20 characters.");
    userpw.focus();
    return false;
  }
  // userpw값과 userpw_ch값이 다르면 실행.
  if (userpw.value != userpw_ch.value) {
    alert("Passwords do not match. Please try again.");
    userpw_ch.focus();
    return false;
  }

  return true;
};
