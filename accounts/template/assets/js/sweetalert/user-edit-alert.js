!function(t){"use strict";t.sweetAlert={confirm:function(t,e,n){document.querySelector(t).addEventListener("click",(function(){Swal.fire({icon:e.icon?e.icon:null,title:e.title?e.title:null,text:e.text?e.text:null,showConfirmButton:void 0===e.showConfirmButton||JSON.parse(e.showConfirmButton),confirmButtonText:e.confirmButtonText?e.confirmButtonText:"Ok",showCancelButton:void 0!==e.showCancelButton&&JSON.parse(e.showCancelButton),cancelButtonText:e.cancelButtonText?e.cancelButtonText:"Cancel",position:e.position?e.position:"center",timer:e.timer?parseInt(e.timer):void 0,timerProgressBar:!!e.timerProgressBar&&e.timerProgressBar}).then(t=>{t.isConfirmed&&Swal.fire({icon:n.icon?n.icon:null,title:n.title?n.title:null,text:n.text?n.text:null,showConfirmButton:void 0===n.showConfirmButton||JSON.parse(n.showConfirmButton),confirmButtonText:n.confirmButtonText?n.confirmButtonText:"Ok",showCancelButton:void 0!==n.showCancelButton&&JSON.parse(n.showCancelButton),cancelButtonText:n.cancelButtonText?n.cancelButtonText:"Cancel",position:n.position?n.position:"center",timer:n.timer?parseInt(n.timer):void 0,timerProgressBar:!!n.timerProgressBar&&n.timerProgressBar})})}))}},t.sweetAlert.init=function(){t.sweetAlert.confirm(".delete-account-button",{title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete it!"},{icon:"success",title:"Deleted!",text:"Your account has been deleted."})},t.winLoad(t.sweetAlert.init)}(NioApp);