						<form id='pin-form' method='post' class='answer-area'>
						<input type='hidden' name='action' value='[@pin-action]' />
						<input type='text' id='pin-display' class='pin-input' name='[@pin_variable]' readonly inputmode='none' placeholder='[@pin_name]' maxlength='[@pin_length]' style='text-align:center;' />
						<br/>
						<br/>
						<table class='pin-pad'>
							<tr>
								<td><button type='button' class='pin-key' value='1'>1</button></td>
								<td><button type='button' class='pin-key' value='2'>2</button></td>
								<td><button type='button' class='pin-key' value='3'>3</button></td>
							</tr>
							<tr>
								<td><button type='button' class='pin-key' value='4'>4</button></td>
								<td><button type='button' class='pin-key' value='5'>5</button></td>
								<td><button type='button' class='pin-key' value='6'>6</button></td>
							</tr>
							<tr>
								<td><button type='button' class='pin-key' value='7'>7</button></td>
								<td><button type='button' class='pin-key' value='8'>8</button></td>
								<td><button type='button' class='pin-key' value='9'>9</button></td>
							</tr>
							<tr>
								<td><button type='button' id='pin-clear' class='pin-clear'>C</button></td>
								<td><button type='button' class='pin-key' value='0'>0</button></td>
								<td><button type='submit' id='pin-submit' class='pin-submit'>></button></td>
							</tr>
						</table>
					</form>
					<script>
						document.querySelectorAll('.pin-key').forEach(button => {
							button.addEventListener('click', () => {
								if (document.getElementById('pin-display').value.length < [@pin_length]) {
									document.getElementById('pin-display').value += button.value;
								}
							});
						});
						document.getElementById('pin-clear').addEventListener('click', () => { document.getElementById('pin-display').value = ''; });
					</script>
