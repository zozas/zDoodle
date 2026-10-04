					<form id='template-form' method='post' class='answer-area' enctype='multipart/form-data'>
						<input type='hidden' name='action' value='[@pin-action]' />
						[@submit]
						<table>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td align='center'>
									[@pin_name]
								</td>
							</tr>
							<tr>
								<td align='center'>
									<input type='text' id='pin' class='pin-input' name='pin' pattern='[0-9#]{1,[@pin_length]}' placeholder='[@pin_name]' maxlength='[@pin_length]' style='text-align:center;' />
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td align='center'>
									[@admin_pin_name]
								</td>
							</tr>
							<tr>
								<td align='center'>
									<input type='text' id='admin_pin' class='pin-input' name='admin_pin' pattern='[0-9#]{1,[@pin_length]}' placeholder='[@admin_pin_name]' maxlength='[@pin_length]' style='text-align:center;' />
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td align='center'>
									<input type='file' name='quiz_upload' id='quiz_upload' class='quiz_upload'>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td align='center'>
									<button type='submit' class='menu-btn'>[@submit]</button>
								</td>
							</tr>
						</table>
					</form>
