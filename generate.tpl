					<form id='pin-form' method='post' class='answer-area'>
						<input type='hidden' name='action' value='admin_generate_file' />
						<h4>[@doodledata]</h4>
						<br />
						<input type='text' id='admin' name='admin' placeholder='[@adminpin]' class='pin-input' required maxlength='[@pin_length]' pattern='[0-9]{1,[@pin_length]}' oninput="this.value = this.value.replace(/[^0-9]/g, '')">
						<br />
						<br />
						<input type='text' id='pin' name='pin' placeholder='[@pin]' class='pin-input' required  maxlength='[@pin_length]' pattern='[0-9]{1,[@pin_length]}' oninput="this.value = this.value.replace(/[^0-9]/g, '')">
						<br />
						<br />
						<input type='text' id='author' name='author' placeholder='[@author]' class='pin-input' required pattern="[^\"'\/]+" oninput='validateInput(this)'>
						<br />
						<br />
						<input type='number' id='duration' name='duration' placeholder='[@duration] [@minutes]' class='pin-input' min='1' step='1' required placeholder='30'>
						<br />
						<br />
						<input type='text' id='instructions' placeholder='[@instructions]' class='pin-input' pattern="[^\"'\/]+" oninput='validateInput(this)'>
						<br />
						<br />
						<input type='text' id='language' name='language' placeholder='[@language]' class='pin-input' required pattern="[^\"'\/]+" oninput='validateInput(this)'>
						<br />
						<br />
						<input type='text' id='title' name='title' placeholder='[@title]' class='pin-input' required pattern="[^\"'\/]+" oninput='validateInput(this)'>
						<br />
						<br />
						<input type='text' id='version' name='version' placeholder='[@version]' class='pin-input' required pattern="[^\"'\/]+" oninput='validateInput(this)'>
						<br />
						<br />
						<h4>[@date_list]</h4>
						<br />
						<div id='doodlesContainer'>
							<div class='doodle-row'>
								<input type='datetime-local' class='date-input' name='doodles[]' class='doodle-input' required>&nbsp;&nbsp;<button type='button' class='remove-btn' onclick='removeDoodleRow(this)'></button>
								<br />
								<br />
							</div>
						</div>
						<br />
						<button type='button' class='admin-btn' onclick='addDoodleRow()'>[@question_add]</button>
						<br />
						<br />
						<button type='submit' class='admin-btn'>[@generate]</button>
					</form>
					<script>
					function addDoodleRow() {
						const container = document.getElementById('doodlesContainer');
						const row = document.createElement('div');
						row.className = 'doodle-row';
						row.innerHTML = `<input type="datetime-local" class='date-input' name="doodles[]" class="doodle-input" required>&nbsp;&nbsp;<button type="button" class="remove-btn" onclick="removeDoodleRow(this)"></button><br /><br />`;
						container.appendChild(row);
					}
					function removeDoodleRow(button) {
						const container = document.getElementById('doodlesContainer');
						if (container.children.length > 1) {
							button.parentElement.remove();
						}
					}
					function validateInput(el) {
						el.value = el.value.replace(/["'\/]/g, "");
					}
					</script>
