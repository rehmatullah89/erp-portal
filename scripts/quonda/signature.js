/*************************************************
 Signsend - RB-Signature Pad
 Author: Rehmat Ullah Bhatti<rehmatullahbhatti@gmail.com>
 **************************************************/
var rbSignature = (function () {

	var empty = true;

	return {
		//public functions
		capture: function ()
                {
				var parent = document.getElementById("canvas");
				parent.childNodes[0].nodeValue = "";

				var canvasArea = document.createElement("canvas");
				canvasArea.setAttribute("id", "newSignature");
				parent.appendChild(canvasArea);

				var canvas = document.getElementById("newSignature");
				var context = canvas.getContext("2d");

				if (!context) {
					throw new Error("Failed to get canvas 2d context");
				}

				screenwidth = screen.width;

				if (screenwidth < 480) {
					canvas.width = screenwidth - 8;
					canvas.height = (screenwidth * 0.63);
				} 
                                else 
                                {
					canvas.width = 890;
					canvas.height = 304;
				}

				context.fillStyle = "#fff";
				context.strokeStyle = "#444";
				context.lineWidth = 1.2;
				context.lineCap = "round";

				context.fillRect(0, 0, canvas.width, canvas.height);

				context.fillStyle = "#3a87ad";
				context.strokeStyle = "#3a87ad";
				context.lineWidth = 1;
				context.moveTo((canvas.width * 0.042), (canvas.height * 0.7));
				context.lineTo((canvas.width * 0.958), (canvas.height * 0.7));
				context.stroke();

				context.fillStyle = "#fff";
				context.strokeStyle = "#444";

				var disableSave = true;
				var pixels = [];
				var cpixels = [];
				var xyLast = {};
				var xyAddLast = {};
				var calculate = false;
				//functions
				{
					function remove_event_listeners() {
						canvas.removeEventListener('mousemove', on_mousemove, false);
						canvas.removeEventListener('mouseup', on_mouseup, false);
						canvas.removeEventListener('touchmove', on_mousemove, false);
						canvas.removeEventListener('touchend', on_mouseup, false);

						document.body.removeEventListener('mouseup', on_mouseup, false);
						document.body.removeEventListener('touchend', on_mouseup, false);
					}

					function get_board_coords(e) {
						var x, y;

						if (e.changedTouches && e.changedTouches[0]) {
							var offsety = canvas.offsetTop || 0;
							var offsetx = canvas.offsetLeft || 0;

							x = e.changedTouches[0].pageX - offsetx;
							y = e.changedTouches[0].pageY - offsety;
						} else if (e.layerX || 0 == e.layerX) {
							x = e.layerX;
							y = e.layerY;
						} else if (e.offsetX || 0 == e.offsetX) {
							x = e.offsetX;
							y = e.offsetY;
						}

						return {
							x : x,
							y : y
						};
					};

					function on_mousedown(e) {
						e.preventDefault();
						e.stopPropagation();

						canvas.addEventListener('mousemove', on_mousemove, false);
						canvas.addEventListener('mouseup', on_mouseup, false);
						canvas.addEventListener('touchmove', on_mousemove, false);
						canvas.addEventListener('touchend', on_mouseup, false);

						document.body.addEventListener('mouseup', on_mouseup, false);
						document.body.addEventListener('touchend', on_mouseup, false);

						empty = false;
						var xy = get_board_coords(e);
						context.beginPath();
						pixels.push('moveStart');
						context.moveTo(xy.x, xy.y);
						pixels.push(xy.x, xy.y);
						xyLast = xy;
					};

					function on_mousemove(e, finish) {
						e.preventDefault();
						e.stopPropagation();

						var xy = get_board_coords(e);
						var xyAdd = {
							x : (xyLast.x + xy.x) / 2,
							y : (xyLast.y + xy.y) / 2
						};

						if (calculate) {
							var xLast = (xyAddLast.x + xyLast.x + xyAdd.x) / 3;
							var yLast = (xyAddLast.y + xyLast.y + xyAdd.y) / 3;
							pixels.push(xLast, yLast);
						} else {
							calculate = true;
						}

						context.quadraticCurveTo(xyLast.x, xyLast.y, xyAdd.x, xyAdd.y);
						pixels.push(xyAdd.x, xyAdd.y);
						context.stroke();
						context.beginPath();
						context.moveTo(xyAdd.x, xyAdd.y);
						xyAddLast = xyAdd;
						xyLast = xy;

					};

					function on_mouseup(e) {
						remove_event_listeners();
						disableSave = false;
						context.stroke();
						pixels.push('e');
						calculate = false;
					};

				}

				canvas.addEventListener('mousedown', on_mousedown, false);
				canvas.addEventListener('touchstart', on_mousedown, false);

		}
		,
		save : function(id, type, date){

                    if(empty == true)
                    {
                        alert("Please add signatures first to save.");
                        return false;
                    }
                    else
                    {
                        // save canvas image as data url (jpeg format by default)
                        var canvas  = document.getElementById("newSignature");
                        var dataURL = canvas.toDataURL("image/jpeg");

                        var xtr = new XMLHttpRequest();
                        xtr.open("POST", 'includes/quonda/saveImage.php?id='+id+"&type="+type+"&date="+date, true);
                        xtr.setRequestHeader('Content-type', 'application/upload');
                        xtr.send(dataURL);
                        
                        xtr.onreadystatechange = function(){
                            if(xtr.readyState == 4 && xtr.responseText == "ok"){
                                    window.location = "includes/quonda/edit-report-section.php?AuditId="+id+"&Section=8";
                            }
                        }     
                    }
		}
		,
		clear : function(){
				var parent = document.getElementById("canvas");
				var child = document.getElementById("newSignature");
				parent.removeChild(child);
				empty = true;
				this.capture();
		}
		,
		send : function(){
                    
                    if (empty == false)
                    {
                        var canvas = document.getElementById("newSignature");
                        var dataURL = canvas.toDataURL("image/jpeg");
                        document.getElementById("saveSignature").src = dataURL;
                        var sendemail = document.getElementById('sendemail').value;
                        var replyemail = document.getElementById('replyemail').value;

                        var dataform = document.createElement("form");
                        document.body.appendChild(dataform);
                        dataform.setAttribute("action","upload_file.php");
                        dataform.setAttribute("enctype","multipart/form-data");
                        dataform.setAttribute("method","POST");
                        dataform.setAttribute("target","_self");
                        dataform.innerHTML = '<input type="text" name="image" value="' + dataURL + '"/>' + '<input type="email" name="email" value="' + sendemail + '"/>' + '<input type="email" name="replyemail" value="' + replyemail + '"/>'+'<input type="submit" value="Send Email" />';
                        dataform.submit();
                    }
		}

	}

})()

var rbSignature;
