<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.
*/
include('../include/database.php');
include('../include/class.mysql.php');
include('../include/config.php');

if (!empty($_SETTINGS['FORCESSL']) && $_SETTINGS['FORCESSL'] == true && $_SERVER['SERVER_PORT'] != 443) {
	header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	exit();
}

$server = '.';
if (!empty($_SETTINGS['CDN'])) {
	$server = 'https://' . $_SETTINGS['CDN'] . '/livehelp/admin';
}

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
  <head>
  
	<meta charset="utf-8">

	<title>Live Help Messenger Web App</title>
	<meta name="description" content="Live Help Messenger Web App" />
	<meta name="author" content="Stardevelop Pty Ltd" />
  
	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" href="<?php echo($server); ?>/styles/style.min.css"/>
	
	<!-- JavaScript -->
	<script type="text/javascript" src="<?php echo($server); ?>/scripts/scripts.min.js"></script>
	<script type="text/javascript" src="<?php echo($server); ?>/scripts/admin.min.js"></script>

	<!-- IE9 Web Application Meta Data -->
	<meta name="application-name" content="Live Help" />
	<meta name="msapplication-tooltip" content="Live Help Web App" />
	<meta name="msapplication-starturl" content="<?php echo($server); ?>/" />
	<meta name="msapplication-starturl" content="<?php echo($server); ?>/index.php" />
	<meta name="msapplication-navbutton-color" content="#69ABCF" />
	<meta name="msapplication-TileImage" content="./images/Win8Tile.png"/>
	<meta name="msapplication-TileColor" content="#E2E2E2"/>

	<link rel="shortcut icon" href="<?php echo($server); ?>/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo($server); ?>/apple-touch-icon.png" />
	
	<!-- Cell Template -->
	<script type="text/html" id="history_template">
		<div class="cell-inner">
			<div class="cell-left">
				<img src='<%=UserAgent%>'/>
			</div>
			<div class="cell-main">
			  <h2 style="text-transform: none"><%=Username%></h2>
			  <span><%=Operator%></span><br/>
			  <span><%=Department%></span><br/>
			  <span><%=Hostname%></span><br/>
			  <span><%=Email%></span><br/>
			  <span><%=Referrer%></span><br/>
			  <span><%=CurrentPage%></span><br/>
			  <span><%=Location%></span><br/>
			  <span><%=Rating%></span><br/>
			</div>
		</div>
	</script>
	<script type="text/html" id="account_template">
		<div class="cell-inner" data-id="<%=ID%>">
			<div class="cell-left">
				<img src='<%=Image%>'/>
			</div>
			<div class="cell-main">
				<div class="cell-heading name"><%=Firstname%> <%=Lastname%></div>
				<span class="cell-details department"><%=Department%></span>
				<span class="cell-details status"><%=Status%></span>
			</div>
		</div>
	</script>
  </head>

  <body>

	<!-- Background -->
	<div class="background gradient"></div>
	<div class="background-bottom gradient"></div>

	<!-- Login -->
	<div class="loading" style="position: absolute; width: 500px; margin: 50px auto; z-index: 20; opacity: 1.0; width: 100%; display: block">
		<div style="position: relative; width: 350px; margin: 0 auto; padding-top: 200px">
			<div style="margin: 0 auto">
				<div style="position:absolute; left:15px; bottom:5px" class="progressring">
					<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity:0.5"/>
				</div>
				<div style="position:absolute; bottom:0; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px">Loading Live Help</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px">Thank you for your patience</div>
				</div>
			</div>
		</div>
	</div>
	<div class="login" style="position: absolute; width: 500px; margin: 50px auto; z-index: 20; opacity: 1.0; width: 100%; display: block; display: none">
		<div class="logo sprite Logo" style="position: relative; margin: 0 auto"></div>
		<div class="inputs" style="position: relative; width: 500px; background: #e3e3e3; margin: 15px auto; padding-bottom: 70px; border-radius: 40px">
			<div style="margin: 0 auto; padding-top: 30px; width: 325px">
				<div class="error" style="text-align: center; width: 325px; height: 25px; display: none">
					<span style="display: inline-block; margin: 0 5px" class="sprite Error"></span>
					<span style="display: inline-block; margin: 0; line-height: 25px; vertical-align: top">Incorrect Username / Password</span>
				</div>
				<div style="margin-top: 15px; width: 325px">
					<div style="display:none">
						<label for="server">Server</label>
						<input id="server" name="server" type="text" />
					</div>
					<label for="username">Username</label>
					<input id="username" name="username" type="text" />
					<label for="password">Password</label>
					<input id="password" name="password" type="password" />
					<label for="status">Status Mode</label>
					<select id="status" name="status" style="font-size: 20px; line-height: 20px; height: 38px; width: 310px; margin: 10px 0 20px 0">
						<option value="Online">Online</option>
						<option value="Offline">Offline (Hidden)</option>
						<option value="BRB">Be Right Back</option>
						<option value="Away">Away</option>
					</select>
					<div style="margin: 10px 0 20px 0; display: none">
						<input id="remember" name="remember" type="checkbox" />
						<label for="remember" style="display: inline; font-size: 18px">Remember for 14 days</label>
					</div>
				</div>
			</div>
			<div class="twofactorparent" style="display: none">
				<div style="margin: 0 auto; width: 400px">
					<label style="display: none">Select Two Factor Authentication:</label>
					<div class="twofactor">
						<div class="factor" data-factor="push" style="display: inline-block; text-align: center">
							<div class="sprite Smartphone"></div><br/>
							<span>Duo PUSH</span>
						</div>
						<div class="factor" data-factor="token" style="display: inline-block; text-align: center">
							<div class="sprite Token"></div><br/>
							<span>Hardware Token</span>
						</div>
						<div class="factor" data-factor="sms" style="display: inline-block; text-align: center">
							<div class="sprite Mobile"></div><br/>
							<span>SMS</span>
						</div>
						<div class="factor" data-factor="telephone" style="display: inline-block; text-align: center">
							<div class="sprite Telephone"></div><br/>
							<span>Telephone</span>
						</div>
					</div>
				</div>
				<div style="margin: 0 auto; width: 325px">
					<div class="twofactorcode">
						<div class="error" style="display: none">
							<label></label>
						</div>
						<div class="code" style="display: none">
							<label for="twofactor">Code</label>
							<input id="twofactor" name="twofactor" type="password" />
							<span class="hint-sms" style="font-size: 14px; display: none"></span>
							<span class="hint-token" style="font-size: 14px; display: none"></span>
						</div>
						<div class="status" style="text-align: center">
							<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0"/>
							<span>Select Two Factor Authentication and Authenticate</span>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-toolbar" style="position:absolute; bottom:15px; right:90px">
				<button class="btn btn-large clear" style="margin:3px 10px">Clear</button>
				<button class="btn btn-large btn-info signin" style="margin:3px 10px">Sign In</button>
			</div>
		</div>
		<div style="margin: 0 auto; font-size: 14px; color: #999; width: 400px; text-align: center; font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif">Please use the latest version of Internet Explorer, Google Chrome, Firefox or Safari</div>
	</div>
  
	<div class="content" style="position: absolute; opacity: 1.0; z-index: 10; width: 100%; height: 100%; opacity: 0">
	
		<!-- Notifications -->
		<div class="notification">
			<div class="icon"></div>
			<div class="notify" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif">User is pending for Live Chat</div>
			<div class="title">Pending Chat</div>
			<div class="close sprite CloseButtonWhite"></div>
		</div>
		
		<!-- Operator -->
		<div class="operator" style="position:absolute; right:25px; top:10px; z-index:100; min-width:200px">
			<div class="photo" style="width:50px; height:50px; float:left; margin-right:10px"></div>
			<div class="name" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size:20px">Administrator Account</div>
			<div class="btn-group" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-weight:100; clear:right; display:block; top:3px">
				<div class="dropdown-toggle" data-toggle="dropdown"><span class='status'>Online</span> <span class="caret"></span></div>
				<ul class="dropdown-menu statusmode">
					<li><a href="#" class="Online">Online</a></li>
					<li><a href="#" class="Offline">Offline (Hidden)</a></li>
					<li><a href="#" class="BRB">Be Right Back</a></li>
					<li><a href="#" class="Away">Away</a></li>
					<li class="divider"></li>
					<li><a href="#" class="Signout">Sign Out</a></li>
				</ul>
			</div>
		</div>

		<!-- Visitor / Chats Totals -->
		<div style="position:absolute; right:250px; top:10px; z-index:100; overflow:hidden">
			<div style="display:inline-block; position:relative; top:4px" title="Browsing Visitors" class="sprite VisitorsTotal"></div>
			<div id="visitortotal" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-weight:100; font-size: 38px; color: #aaa; display:inline-block; line-height: 38px" title="Browsing Visitors">0</div>
			<div style="margin-left: 20px; display:inline-block; position:relative; top:4px" title="Chatting Visitors" class="sprite ChatsTotal"></div>
			<div id="chatstotal" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-weight:100; font-size: 38px; color: #aaa; display:inline-block; line-height: 38px" title="Chatting Visitors">0</div>
		</div>
		
		<!-- Logo -->
		<div class="logo sprite Logo"></div>
		
		<!-- Chatting / Pending Visitors -->
		<div class="scroll" style="position:absolute; top:100px; left:15px; bottom:250px; width:250px; overflow:auto">
			<div class="chat-list-heading operators" style="margin-top: 0">Online Operators<div class="expander"></div></div>
			<div id="operators" class="operators list" data-height="38">
				<div class="no-visitor">
					<span>No Operators</span>
				</div>
			</div>
			<div class="chat-list-heading chatting">Chatting Visitors<div class="expander"></div></div>
			<div id="chatting" class="chatting list" data-height="38">
				<div class="no-visitor">
					<span>No Visitors</span>
				</div>
			</div>
			<div class="chat-list-heading other-chatting">Other Chatting Visitors<div class="expander sprite sort-desc"></div></div>
			<div id="other-chatting" class="other-chatting list" data-height="38" style="height:0; display:none">
				<div class="no-visitor">
					<span>No Visitors</span>
				</div>
			</div>
			<div class="chat-list-heading pending">Pending Visitors<div class="expander"></div></div>
			<div id="pending" class="pending list" data-height="38">
				<div class="no-visitor">
					<span>No Visitors</span>
				</div>
			</div>
		</div>
		
		<!-- Current Chat -->
		<div class="chat-stack">
			<div style="position: absolute; top: 10px; left: 500px; z-index: 550">
				<div class="btn dropdown-toggle options" data-toggle="dropdown" title="Options" style="margin-top: 20px">Options</div>
				<ul class="dropdown-menu options">
					<li class="text"><a href="#">Text</a></li>
					<li class="hyperlink"><a href="#">Hyperlink</a></li>
					<li class="image"><a href="#">Images</a></li>
					<li class="push"><a href="#">PUSH</a></li>
					<li class="javascript"><a href="#">JavaScript</a></li>
					<li class="divider"></li>
					<li><a href="#" class="Close">Close Chat</a></li>
					<li><a href="#" class="Block">Block Chat</a></li>
					<li class="divider"></li>
					<li class="dropdown-submenu">
						<a href="#">Email Chat Transcript</a>
						<ul class="dropdown-menu">
							<li><a href="#" class="EmailChatOffline">Offline Email Address</a></li>
							<li><a href="#" class="EmailChatVisitor">Visitor's Email Address</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div style="position: absolute; bottom: 0; left: 15px; width: 570px; height: 140px; z-index: 550">
				<textarea class="input-xlarge" id="message" style="width: 460px"></textarea>
				<div style="position:absolute; bottom:130px; right:85px">
					<div class="smilies button sprite Smilies" title="Smilies" style="top: 15px"></div>
					<div class="search button sprite Search" title="Search Responses" style="top: 15px"></div><br/>
				</div>
				<div class="btn-toolbar">
					<div class="send sprite SendButton" style="left: 10px; opacity: 0.4; position: relative; top: 25px" title="Send"></div><br/>
				</div>
			</div>
			<div class="dialog" style="position: absolute; bottom: -145px; left: 1px; width: 600px; background-color: #e5e5e5; height: 145px; z-index: 600; display: none;">
				<div style="position:absolute; bottom: 55px; left:25px" class="progressring">
					<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0.5">
				</div>
				<div style="position:absolute; top: 30px; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px" class="dialog-title">Closing Chat Session</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px" class="dialog-description">One moment while the chat session is closed.</div>
				</div>
				<div class="btn unblock" title="Unblock Chat" style="position: absolute; right: 15px; bottom: 15px; display:none">Unblock Chat</div>
			</div>
			<div id="SmiliesTooltip">
				<div><span title="Laugh" class="sprite Laugh"></span><span title="Smile" class="sprite Smile"></span><span title="Sad" class="sprite Sad"></span><span title="Money" class="sprite Money"></span><span title="Impish" class="sprite Impish"></span><span title="Sweat" class="sprite Sweat"></span><span title="Cool" class="sprite Cool"></span><br/><span title="Frown" class="sprite Frown"></span><span title="Wink" class="sprite Wink"></span><span title="Surprise" class="sprite Surprise"></span><span title="Woo" class="sprite Woo"></span><span title="Tired" class="sprite Tired"></span><span title="Shock" class="sprite Shock"></span><span title="Hysterical" class="sprite Hysterical"></span><br/><span title="Kissed" class="sprite Kissed"></span><span title="Dizzy" class="sprite Dizzy"></span><span title="Celebrate" class="sprite Celebrate"></span><span title="Angry" class="sprite Angry"></span><span title="Adore" class="sprite Adore"></span><span title="Sleep" class="sprite Sleep"></span><span title="Quiet" class="sprite Stop"></span></div>
			</div>
		</div>
		
		<!-- Home -->
		<div class="visitors-grid" style="position:absolute; top:130px; left:270px; right:15px; bottom:215px"></div>
		<div class="visitors-empty" style="position:absolute; top:130px; left:270px; right:15px; bottom:215px; text-align:center; display:none">
			<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: auto; position: absolute; top: 50%; width: 100%">No Browsing Visitors</div>
		</div>
		
		<!-- Statistics -->
		<div class="statistics" style="position: absolute; top: 100px; left: 270px; right: 0; bottom: 200px; display: none">
			<div style="font-size: 22px; line-height: normal; margin-left: 50px">Chat Rating / Feedback</div>
			<div style="position: relative; width: 600px; height: 60%; top: 0; bottom: 0" class="chart-container">
				<div id="rating-chart" style="position: absolute; top: 0; width: 600px; height: 100%; z-index: 10"></div>
				<div id="rating-empty" style="position: absolute; top: 0; text-align: center; width: 600px; height: 100%; z-index: 20; display:none">
					<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: 0 auto; position: absolute; top: 42%; left: 38%; background: rgba(238, 237, 238, 1.0); padding: 15px 10px; border-radius: 10px">No Ratings</div>
				</div>
			</div>
			<div style="position: absolute; top: 0; left: 600px; bottom: 0; right: 0;" class="weekday">
				<div style="font-size: 22px; line-height: normal">Average Chats / Day</div>
				<div style="position: relative; width: 100%; height: 60%" class="chart-container">
					<div id="weekday-chart" style="position: absolute; top: 0; width: 100%; height: 100%; z-index: 10"></div>
					<div id="weekday-empty" style="position: absolute; top: 0; text-align: center; width: 600px; height: 100%; z-index: 20; display:none">
						<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: 0 auto; position: absolute; top: 38%; left: 30%; background: rgba(238, 237, 238, 1.0); padding: 15px 10px; border-radius: 10px">Chat Average Unavailable</div>
					</div>
				</div>
				<div>
					<div style="background: url(images/ChatTime.png) no-repeat; width: 32px; height: 32px; display: inline-block; margin-right: 15px"></div>
					<div style="display: inline-block">
						<div style="font-size: 16px; line-height: normal">Average Chat Time:</div>
						<div style="font-size: 22px; line-height: normal" class="averagechattime">Unavailable</div>
					</div>
				</div>
				<div style="margin-top: 5px">
					<div style="background: url(images/ChatRating.png) no-repeat; width: 32px; height: 32px; display: inline-block; margin-right: 15px"></div>
					<div style="display: inline-block">
						<div style="font-size: 16px; line-height: normal">Average Chat Rating:</div>
						<div style="font-size: 22px; line-height: normal" class="averagechatrating">Unavailable</div>
					</div>
				</div>
			</div>
			<div style="position: relative; width: 450px; margin-left: 75px">
				<div style="margin-bottom: 5px">
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<span style="margin-left:5px">Excellent</span>
					<span class="rating histogram">
						<span class="value excellent"></span>
					</span>
				</div>
				<div style="margin-bottom: 5px">
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating"></div>
					<span style="margin-left:5px">Very Good</span>
					<span class="rating histogram">
						<span class="value verygood"></span>
					</span>
				</div>
				<div style="margin-bottom: 5px">
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<span style="margin-left:5px">Good</span>
					<span class="rating histogram">
						<span class="value good"></span>
					</span>
				</div>
				<div style="margin-bottom: 5px">
					<div class="rating-highlight"></div>
					<div class="rating-highlight"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<span style="margin-left:5px">Poor</span>
					<span class="rating histogram">
						<span class="value poor"></span>
					</span>
				</div>
				<div style="margin-bottom: 5px">
					<div class="rating-highlight"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<span style="margin-left:5px">Very Poor</span>
					<span class="rating histogram">
						<span class="value verypoor"></span>
					</span>
				</div>
				<div style="margin-bottom: 5px">
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<div class="rating"></div>
					<span style="margin-left:5px">Unrated</span>
					<span class="rating histogram">
						<span class="value unrated"></span>
					</span>
				</div>
			</div>
		</div>

		<!-- History Calendar -->
		<div class="history" style="position: absolute; top: 110px; left: 270px; right: 0; bottom: 200px; display: none">
			<div style="position: absolute; width: 300px; height: 100%">
				<div id="calendar" style="position: relative; left: 0"></div>
				<div class="chart" style="position: relative; top: 0">
					<div style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size:22px; color: #999; margin: 10px 0 0 0; text-align: center">Recent Chats - Last 7 Days</div>
					<div style="position: relative; width: 300px; height: 200px; top: 0; bottom: 0">
						<div id="history-chart" style="position: absolute; top: 0; width: 300px; height: 200px; z-index: 10"></div>
						<div id="history-empty" style="position: absolute; top: 0; text-align: center; width: 300px; height: 200px; z-index: 20; display:none">
							<div style="background: #eeedee; opacity: 0.75; width: 100%; height: 100%"></div>
							<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: 0 auto; position: absolute; top: 40%; left: 15%; background: #eeedee; opacity: 1.0; padding: 15px 10px; border-radius: 10px">No Chat History</div>
						</div>
					</div>
				</div>
			</div>
			<div style="position:absolute; top:0; left:290px; bottom:0; right:0">
				<div class="history-grid" style="position:absolute; top:0; bottom:0; left:0; right:0"></div>
				<div class="history-empty" style="position: relative; width: 100%; height: 100%; text-align: center; display: none">
					<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: auto; position: absolute; top: 50%; width: 100%">No Chat History</div>
				</div>
			</div>
		</div>
		
		<!-- Menu -->
		<div class="menu-container">
			<ul class="menu">
				<li><a href="#" data-type="home" class="selectedMenu">Home</a></li>
				<li><a href="#" data-type="statistics">Statistics</a></li>
				<li><a href="#" data-type="history">History</a></li>
				<li><a href="#" data-type="responses">Responses</a></li>
				<li><a href="#" data-type="accounts">Accounts</a></li>
				<li><a href="#" data-type="settings">Settings</a></li>
			</ul>
		</div>

		<!-- History Chat -->
		<div id="history-chat" class="slider right">
			<div class="back" style="display:none"></div>
			<div class="close"></div>
			<div class="name">Steve</div>
			<div class="scroll" style="bottom:10px; width:98%">
				<div class="messages"></div>
			</div>
			<div class="dialog" style="position: absolute; bottom: -145px; left: 1px; width: 100%; background-color: #e5e5e5; height: 145px; z-index: 600; display: none;">
				<div style="position:absolute; bottom: 55px; left:25px" class="progressring">
					<div class="sprite Block" style="opacity: 0.5"></div>
				</div>
				<div style="position:absolute; top: 30px; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px" class="dialog-title">Chat Session Blocked</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px" class="dialog-description">The chat session is blocked and inactive.</div>
				</div>
				<div class="btn unblock" title="Unblock Chat" style="position: absolute; right: 15px; bottom: 15px">Unblock Chat</div>
			</div>
		</div>

		<!-- Pre-typed Responses -->
		<div id="responses" class="slider right">
			<div class="back" style="display:none"></div>
			<div class="close"></div>
			<div class="details" style="bottom:10px">
				<div><span class="header">Pre-typed Responses</span></div>
				<div class="scroll" style="top:100px; bottom:50px; left:25px; width:95%">
					<div id="response-list"></div>
					<div id="add-response" style="display:none">
						<input id="ResponseID" type="hidden" value="" />
						<div class="label">Name</div>
						<div class="LiveHelpInput">
							<input id="ResponseName" type="text" value="" />
							<span id="ResponseNameError" title="Name Required" class="sprite InputError"></span>
						</div>
						<div class="label">Category</div>
						<div class="LiveHelpInput">
							<input id="ResponseCategory" type="text" value="" />
							<span id="ResponseCategoryError" title="Category Required" class="sprite InputError"></span>
						</div>
						<div class="label">Type</div>
						<div class="LiveHelpInput checkbox">
							<input id="ResponseTypeText" name="type" data-type="Text" value="0" type="radio" style="top: -2px" checked="checked" />
							<label for="ResponseTypeText">Text</label>
							<input id="ResponseTypeHyperlink" name="type" data-type="Hyperlink" value="-1" type="radio" style="top: -2px" />
							<label for="ResponseTypeHyperlink">Hyperlink</label>
							<input id="ResponseTypeImage" name="type" data-type="Image" value="-1" type="radio" style="top: -2px" />
							<label for="ResponseTypeImage">Image</label>
							<input id="ResponseTypePUSH" name="type" data-type="PUSH" value="-1" type="radio" style="top: -2px" />
							<label for="ResponseTypePUSH">PUSH</label>
							<input id="ResponseTypeJavaScript" name="type" data-type="JavaScript" value="-1" type="radio" style="top: -2px" />
							<label for="ResponseTypeJavaScript">JavaScript</label>
						</div>
						<div class="URL" style="display:none">
							<div class="label">URL</div>
							<div class="LiveHelpInput">
								<input id="ResponseURL" type="text" value="" />
								<span id="ResponseURLError" title="URL Required" class="sprite InputError"></span>
							</div>
						</div>
						<div class="Content">
							<div class="label">Content</div>
							<div class="LiveHelpInput">
								<textarea id="ResponseContent" style="width:400px; height:150px; resize:none; margin: 0 3px 5px 3px"></textarea>
								<span id="ResponseContentError" title="Content Required" class="sprite InputError"></span>
							</div>
						</div>
						<div class="label">Tags</div>
						<div class="LiveHelpInput">
							<input id="ResponseTags" type="text" value="" />
							<button class="btn add-tag" style="margin:3px 10px">Add Tag</button>
							<span id="ResponseTagsError" title="Tags Required" class="sprite InputError"></span>
							<div class="add-response tags"></div>
						</div>
					</div>
				</div>
				<div class="search" style="position:absolute; left:25px; bottom:0; width:100%">
					<input id="search" type="text" placeholder="Search Pre-typed Responses" style="width:80%" />
					<div class="search button sprite Search" title="Search Responses" style="top:6px"></div>
					<div class="add-small button" title="Add Response" style="top:6px"></div>
				</div>
			<div class="button-toolbar save" style="display: none; text-align: center; margin: 0 auto; position: absolute; z-index: 10">
				<div class="save button">
					<div class="save-button"></div>
					<div class="text">save</div>
				</div>
				<div class="cancel button">
					<div class="cancel-button"></div>
					<div class="text">cancel</div>
				</div>
			</div>
			<div class="button-toolbar edit" style="display: none; text-align: center; margin: 0 auto; position: absolute; z-index: 10">
				<div class="delete button">
					<div class="delete-button"></div>
					<div class="text">delete</div>
				</div>
				<div class="save button">
					<div class="save-button"></div>
					<div class="text">save</div>
				</div>
				<div class="cancel button">
					<div class="cancel-button"></div>
					<div class="text">cancel</div>
				</div>
			</div>
			<div class="confirm-delete" style="position: absolute; bottom: -90px; background: #e5e5e5; z-index: 1000; font-family:Segoe UI Light, Helvetica Neue, RobotoLight, Segoe UI, Segoe WP, sans-serif; width: 100%; height: 90px">
				<div style="position:absolute; bottom:25px; left:15px" class="progressring">
					<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0"/>
				</div>
				<div style="position:absolute; top:10px; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px">Confirm Account Delete</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px">Are you sure that you wish to delete this account?</div>
				</div>
				<div style="position:absolute; bottom:15px; right:15px" class="buttons">
					<div class="accept-button delete" style="position: relative; margin:3px; display: inline-block" title="Delete"></div>
					<div class="cancel-button cancel" style="position: relative; margin:3px; display: inline-block" title="Cancel"></div>
				</div>
			</div>
			</div>
		</div>

		<!-- Account Details -->
		<div id="account-details" class="slider right">
			<div class="back" style="display:none"></div>
			<div class="close"></div>
			<div id="account-dropzone" style="position: absolute; height: 100%; width: 200px; right: 0px; z-index: 500; display: none"></div>
			<div class="details">
				<div>
					<div>
						<span class="header">Add / Edit Accounts</span>
						<div class="btn-group" style="font-family:'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-weight: 100; display: inline-block; margin-left: 25px; top: -10px; display: none">
							<div class="dropdown-toggle btn" data-toggle="dropdown"><span class="status">Online</span> <span class="caret"></span></div>
							<ul class="dropdown-menu statusmode">
								<li><a href="#" class="Online">Online</a></li>
								<li><a href="#" class="Offline">Offline (Hidden)</a></li>
								<li><a href="#" class="BRB">Be Right Back</a></li>
								<li><a href="#" class="Away">Away</a></li>
							</ul>
						</div>
					</div>
					<div class="image" id="account-image" style="display: none; float: right; margin-right: 15px"></div>
					<div class="upload" style="background: url('images/AccountDragDrop.png'); width: 144px; height: 91px; position: absolute; right: 90px; top: 5px; z-index: 600; display: none"></div>
					<div id="account-upload" style="display: none; position: absolute; top: 35px; right: 25px; z-index: 550; border-radius: 20px; background: #fafafa; border: 2px dashed #CCC">
						<div class="image" style="opacity: 0.5; padding: 20px; width: 60px; height: 60px"></div>
					</div>
				</div>
				<div class="accounts-grid" style="position:absolute; top:90px; left:0; right:0; bottom:100px; overflow-x: hidden"></div>
				<div class="scroll" style="top:110px; bottom:90px; left:25px; width:98%; z-index: 520; display:none">
					<input id="AccountID" type="hidden" value="" />
					<div class="label">Username</div>
					<div class="value username">guest</div>
					<div class="LiveHelpInput" style="display: none">
						<input id="AccountUsername" type="text" value="" />
						<span id="AccountUsernameError" title="Username Required" class="sprite InputError"></span>
					</div>
					<div class="label">Firstname</div>
					<div class="value firstname">Guest</div>
					<div class="LiveHelpInput" style="display: none">
						<input id="AccountFirstname" type="text" value="" />
						<span id="AccountFirstnameError" title="Firstname Required" class="sprite InputError"></span>
					</div>
					<div class="label">Lastname</div>
					<div class="value lastname">Account</div>
					<div class="LiveHelpInput" style="display: none">
						<input id="AccountLastname" type="text" value="" />
						<span id="AccountLastnameError" title="Lastname Required" class="sprite InputError"></span>
					</div>
					<div class="label">Email</div>
					<div class="value email">guest@example.com</div>
					<div class="LiveHelpInput" style="display: none">
						<input id="AccountEmail" type="text" value="" />
						<span id="AccountEmailError" title="Email Required" class="sprite InputError"></span>
					</div>
					<div class="label">Department</div>
					<div class="value department">Sales; Support</div>
					<div class="LiveHelpInput" style="display: none">
						<input id="AccountDepartment" type="text" value="" />
						<span id="AccountDepartmentError" title="Department Required" class="sprite InputError"></span>
					</div>
					<div class="password" style="display: none">
						<div class="label">Password</div>
						<div class="LiveHelpInput" style="display: none">
							<input id="AccountPassword" type="password" value="" />
							<span id="AccountPasswordError" title="Password Required" class="sprite InputError"></span>
						</div>
						<div class="label">Confirm Password</div>
						<div class="LiveHelpInput" style="display: none">
							<input id="AccountPasswordConfirm" type="password" value="" />
							<span id="AccountPasswordConfirmError" title="Confirm Password Required" class="sprite InputError"></span>
						</div>
					</div>
					<div class="label">Access Level</div>
					<div class="value accesslevel">Guest</div>
					<div class="LiveHelpInput" style="display: none">
						<select id="AccountAccessLevel">
							<option value="0">Full Administrator</option>
							<option value="1">Department Administrator</option>
							<option value="2">Limited Administrator</option>
							<option value="3">Sales / Support Staff</option>
							<option value="4">Guest</option>
						</select>
						<span id="AccountAccessLevelError" title="Access Level Required" class="sprite InputError"></span>
					</div>
					<div class="label">Account Status</div>
					<div class="value accountstatus">Enabled</div>
					<div class="LiveHelpInput checkbox" style="display: none">
						<input id="AccountStatusEnable" name="account" value="0" type="radio" style="top: -2px" />
						<label for="AccountStatusEnable">Enable</label>
						<input id="AccountStatusDisable" name="account" value="-1" type="radio" style="top: -2px" />
						<label for="AccountStatusDisable">Disable</label>
					</div>
				</div>
			</div>
			<div class="button-toolbar add" style="text-align: center; margin: 0 auto; position: absolute">
				<div class="add button">
					<div class="add-button"></div>
					<div class="text">add</div>
				</div>
			</div>
			<div class="button-toolbar edit" style="display: none; text-align: center; margin: 0 auto; position: absolute">
				<div class="edit button">
					<div class="edit-button"></div>
					<div class="text">edit</div>
				</div>
				<div class="delete button">
					<div class="delete-button"></div>
					<div class="text">delete</div>
				</div>
				<div class="cancel button">
					<div class="cancel-button"></div>
					<div class="text">cancel</div>
				</div>
			</div>
			<div class="button-toolbar save" style="display: none; text-align: center; margin: 0 auto; position: absolute; z-index: 10">
				<div class="save button">
					<div class="save-button"></div>
					<div class="text">save</div>
				</div>
				<div class="cancel button">
					<div class="cancel-button"></div>
					<div class="text">cancel</div>
				</div>
			</div>
			<div class="confirm-delete" style="position: absolute; bottom: -90px; background: #e5e5e5; z-index: 1000; font-family:Segoe UI Light, Helvetica Neue, RobotoLight, Segoe UI, Segoe WP, sans-serif; width: 100%; height: 90px">
				<div style="position:absolute; bottom:25px; left:15px" class="progressring">
					<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0"/>
				</div>
				<div style="position:absolute; top:10px; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px">Confirm Account Delete</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px">Are you sure that you wish to delete this account?</div>
				</div>
				<div style="position:absolute; bottom:15px; right:15px" class="buttons">
					<div class="accept-button delete" style="position: relative; margin:3px; display: inline-block" title="Delete"></div>
					<div class="cancel-button cancel" style="position: relative; margin:3px; display: inline-block" title="Cancel"></div>
				</div>
			</div>
			<div class="account-dialog" style="position: absolute; bottom: -90px; background: #e5e5e5; z-index: 1000; font-family:Segoe UI Light, Helvetica Neue, RobotoLight, Segoe UI, Segoe WP, sans-serif; width: 100%; height: 90px">
				<div style="position:absolute; bottom:25px; left:15px" class="progressring">
					<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0.5"/>
				</div>
				<div style="position:absolute; top:10px; left:50px">
					<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px" class="title">Adding Account</div>
					<div style="font-size: 16px; font-weight: 100; margin: 0 20px" class="description">One moment while your account is created.</div>
				</div>
			</div>
		</div>
		
		<!-- Visitor Details -->
		<div id="visitor-details" class="slider right">
			<div class="close"></div>
			<div class="details">
				<div id="hostname">User - 127.0.0.1.example.com</div>
				<div class="scroll" style="top:40px; bottom:0; left:0; width:98%">
					<div class="label">Web Browser</div>
					<div class="value useragent">
						<span id="useragent">Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari</span>
					</div>
					<div class="label">Resolution</div>
					<div class="value" id="resolution">1920 x 1080</div>
					<div class="label">Country</div>
					<div class="value"><span id="country">United Kingdom</span><span id="country-image" class="sprite uk" style="margin-left:5px; display:inline-block"></span></div>
					<div class="label">Referrer</div>
					<div class="value" id="referrer"><a href="#" target="_blank">Direct Visit / Bookmark</a></div>
					<div class="label">Current Page</div>
					<div class="value" id="currentpage"><a href="http://livehelp.stardevelop.com/" target="_blank">http://www.example.com/</a></div>
					<div class="label">Chat Status</div>
					<div class="value" id="chatstatus">Live Help Request has not been Initiated</div>
					<div class="label">Page History</div>
					<div class="value" id="pagehistory">/</div>
				</div>
			</div>
		</div>
		
		<!-- Visitor Chart -->
		<div class="metro-pivot" style="height:250px; width:100%; position:absolute; bottom:0; left:0; overflow: hidden">
			<div class="pivot-item">
				<h3>chats</h3>
				<div>
					<div id="chat-chart" style="height: 200px; opacity: 1.0; z-index: 10"></div>
					<div id="chat-empty" style="position: relative; top: -200px; height: 200px; text-align: center; z-index: 20; display: none">
						<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: 0 auto; position: absolute; top: 50%; left: 45%; background: #eeedee; opacity: 1.0; padding: 15px 10px; border-radius: 10px">No Chat Data</div>
					</div>
				</div>
			</div>
			<div class="pivot-item">
				<h3>visitors</h3>
				<div>
					<div id="visitor-chart" style="height:200px; opacity:1.0"></div>
					<div id="visitor-empty" style="position: relative; top: -200px; height: 200px; text-align: center; z-index: 20; display: none">
						<div style="font-family: 'Segoe UI Light', 'Segoe WP Light', 'Helvetica Neue', RobotoLight, sans-serif; font-size: 26px; font-weight: 100; color: #999; vertical-align: middle; margin: 0 auto; position: absolute; top: 50%; left: 45%; background: #eeedee; opacity: 1.0; padding: 15px 10px; border-radius: 10px">No Visitor Data</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Accounts -->
		<div class="settings-parent">
			<div id="settings" class="settings">
				<div class="close"></div>
				<div class="settingsmenu">
					<div id="general"><span>General</span></div>
					<div id="appearance"><span>Appearance</span></div>
					<div id="alerts"><span>Alerts</span></div>
					<div id="images"><span>Images</span></div>
					<div id="startup" style="display:none"><span>Start-up</span></div>
					<div id="htmlcode"><span>HTML Code</span></div>
					<div id="email"><span>Email</span></div>
					<div id="filetransfer" style="display:none"><span>File Transfer</span></div>
					<div id="initiatechat"><span>Initiate Chat</span></div>
					<div id="privacy"><span>Privacy</span></div>
				</div>
				<div class="sections">
					<div class="button-toolbar save" style="position:absolute; bottom:-90px; right:15px; width:120px; z-index:100">
						<div class="save button">
							<div class="save-button"></div>
							<div class="text">save</div>
						</div>
						<div class="cancel button">
							<div class="cancel-button"></div>
							<div class="text">cancel</div>
						</div>
					</div>
					<div class="settings-general section" style="display: block">
						<label for="domainname">Domain Name</label>
						<input id="domainname" name="domainname" type="text" />
						<br/>
						<label for="siteaddress">Site Address</label>
						<input id="siteaddress" name="siteaddress" type="text" />
						<br/>
						<label for="livehelpname">Live Help Name</label>
						<input id="livehelpname" name="livehelpname" type="text" />
						<br/>
						<label for="visitortracking-enable">Visitor Tracking</label>
						<div class="checkbox">
							<input id="visitortracking-enable" name="visitortracking" type="radio"/>
							<label for="visitortracking-enable">Enable</label>
							<input id="visitortracking-disable" name="visitortracking" type="radio" />
							<label for="visitortracking-disable">Disable</label>
						</div>
						<br/>
						<label for="departments-enable">Departments</label>
						<div class="checkbox">
							<input id="departments-enable" name="departments" type="radio"/>
							<label for="departments-enable">Enable</label>
							<input id="departments-disable" name="departments" type="radio" />
							<label for="departments-disable">Disable</label>
						</div>
						<br/>
						<label for="welcomenote">Welcome Note</label>
						<input id="welcomenote" name="welcomenote" type="text" />
						<br/>
						<label for="language">Language</label>
						<select id="language" name="language">
							<option value="en">English</option>
						</select>
					</div>
					<div class='settings-appearance section'>
						<label for="template">Template</label>
						<select id="template" name="template">
							<option value="default">Default</option>
						</select>
						<br/>
						<label for="smilies-enable">Smilies</label>
						<div class="checkbox">
							<input id="smilies-enable" name="smilies" type="radio"/>
							<label for="smilies-enable">Enable</label>
							<input id="smilies-disable" name="smilies" type="radio" />
							<label for="smilies-disable">Disable</label>
						</div>
						<br/>
						<label for="backgroundcolor">Background Color</label>
						<input id="backgroundcolor" name="backgroundcolor" type="text" />
						<br/>
						<label for="generalfont">General Font</label>
						<input id="generalfont" name="generalfont" type="text" style="width:350px" />
						<select id="generalfontsize" name="generalfontsize" style="width:75px">
							<option value="8px">8px</option>
							<option value="9px">9px</option>
							<option value="10px">10px</option>
							<option value="11px">11px</option>
							<option value="12px">12px</option>
							<option value="13px">13px</option>
							<option value="14px">14px</option>
						</select>
						<br/>
						<div>
							<div style="display: inline-block">
								<label for="generalfontcolor" style="display: inline-block">Font Color</label><br/>
								<input id="generalfontcolor" name="generalfontcolor" type="text" />
							</div>
							<div style="display: inline-block; margin-left: 50px">
								<label for="generalfontlinkcolor" style="display: inline-block">Font Link Color</label><br/>
								<input id="generalfontlinkcolor" name="generalfontlinkcolor" type="text" />
							</div>
						</div>
						<label for="guestchatfont">Guest Chat Font</label>
						<input id="guestchatfont" name="guestchatfont" type="text" style="width:350px" />
						<select id="guestchatfontsize" name="guestchatfontsize" style="width:75px">
							<option value="8px">8px</option>
							<option value="9px">9px</option>
							<option value="10px">10px</option>
							<option value="11px">11px</option>
							<option value="12px">12px</option>
							<option value="13px">13px</option>
							<option value="14px">14px</option>
						</select>
						<br/>
						<div>
							<div style="display: inline-block">
								<label for="sentcolor" style="display: inline-block">Sent Color</label><br/>
								<input id="sentcolor" name="sentcolor" type="text" />
							</div>
							<div style="display: inline-block; margin-left: 50px">
								<label for="receivedcolor" style="display: inline-block">Received Color</label><br/>
								<input id="receivedcolor" name="receivedcolor" type="text" />
							</div>
						</div>
						<label for="chatwindowsize">Chat Window Size</label>
						<select id="chatwindowsize" name="chatwindowsize">
							<option value="625 x 435">625 x 435</option>
							<option value="725 x 535">725 x 535</option>
							<option value="825 x 635">825 x 635</option>
						</select>
					</div>
					<div class="settings-alerts section">
						<label for="html5-notifications-enable">HTML5 Notifications</label>
						<div class="html5-notifications checkbox">
							<input id="html5-notifications-enable" name="notification" type="radio"/>
							<label for="html5-notifications-enable">Enable</label>
							<input id="html5-notifications-disable" name="notification" type="radio" />
							<label for="html5-notifications-disable">Disable</label>
						</div>
						<label style="font-size:13px; margin-top:5px">Supports Google Chrome and Safari 6.0 (Mac OS X 10.8 Notification Center)</label>
					</div>
					<div class="settings-images section">
						<label for="logo">Live Help Logo</label>
						<input id="logo" name="logo" type="text" />
						<br/>
						<label for="campaignimage">Campaign Image</label>
						<input id="campaignimage" name="campaignimage" type="text" />
						<br/>
						<label for="campaignlink">Campaign Link</label>
						<input id="campaignlink" name="campaignlink" type="text" />
						<br/>
						<label for="onlineimage">Online Image</label>
						<input id="onlineimage" name="onlineimage" type="text" />
						<br/>
						<label for="offlineimage">Offline Image</label>
						<input id="offlineimage" name="offlineimage" type="text" />
						<br/>
						<label for="berightbackimage">Be Right Back Image</label>
						<input id="berightbackimage" name="berightbackimage" type="text" />
						<br/>
						<label for="awayimage">Away Image</label>
						<input id="awayimage" name="awayimage" type="text" />
						<br/>
					</div>
					<div class="settings-htmlcode section">
						<div class="copy step1"><span class="sprite Copy" style="display:inline-block"></span><span>Copy Code</span></div>
						<label for="htmlcodestep1">HTML Code - Step 1</label>
						<div style="margin-top: 10px">The HTML code below is used to track your site visitors and setup the Live Chat system. Please insert this code between your &lt;head&gt; and &lt;/head&gt; tags.</div>
						<textarea id="htmlcodestep1" style="margin-bottom: 15px"></textarea>
						<br/>
						<div class="copy step2"><span class="sprite Copy" style="display:inline-block"></span><span>Copy Code</span></div>
						<label for="htmlcodestep2">HTML Code - Step 2</label>
						<div style="margin-top: 10px">The HTML code below is used to display the Live Help Online / Offline button. Please place this code where you wish the button to appear.</div>
						<textarea id="htmlcodestep2"></textarea>
						<br/>
					</div>
					<div class='settings-email section'>
						<label for="emailaddress">Offline Email Address</label>
						<input id="emailaddress" name="emailaddress" type="text" />
						<br/>
						<label for="offlineurlredirection">Offline URL Redirection</label>
						<input id="offlineurlredirection" name="offlineurlredirection" type="text" />
						<br/>
						<label for="email-enable">Offline Email</label>
						<div class="checkbox">
							<input id="email-enable" name="email" type="radio"/>
							<label for="email-enable">Enable</label>
							<input id="email-disable" name="email" type="radio" />
							<label for="email-disable">Disable</label>
						</div>
					</div>
					<div class='settings-initiatechat section'>
						<label for="autoinitiatechat-enable">Auto Initiate Chat</label>
						<div class="checkbox">
							<input id="autoinitiatechat-enable" name="autoinitiatechat" type="radio"/>
							<label for="autoinitiatechat-enable">Enable</label>
							<input id="autoinitiatechat-disable" name="autoinitiatechat" type="radio" />
							<label for="autoinitiatechat-disable">Disable</label>
						</div>
						<br/>
						<div class="autoinitiate-pageviews" style="margin-bottom: 25px">
							<label for="autoinitiatechat-pages">Auto Initiate Chat After Page Views</label>
							<select id="autoinitiatechat-pages" name="autoinitiatechat-pages">
								<option value="1">After 1 Pageview</option>
								<option value="2">After 2 Pageviews</option>
								<option value="3">After 3 Pageviews</option>
								<option value="4">After 4 Pageviews</option>
								<option value="5">After 5 Pageviews</option>
								<option value="6">After 6 Pageviews</option>
								<option value="7">After 7 Pageviews</option>
								<option value="8">After 8 Pageviews</option>
								<option value="9">After 9 Pageviews</option>
								<option value="10">After 10 Pageviews</option>
								<option value="11">After 11 Pageviews</option>
								<option value="12">After 12 Pageviews</option>
								<option value="13">After 13 Pageviews</option>
								<option value="14">After 14 Pageviews</option>
								<option value="15">After 15 Pageviews</option>
							</select>
						</div>
						<br/>
						<label for="verticalalignment">Vertical Alignment</label>
						<select id="verticalalignment" name="verticalalignment">
							<option value="Top">Top</option>
							<option value="Center">Center</option>
							<option value="Bottom">Bottom</option>
						</select>
						<br/>
						<label for="horizontalalignment">Horizontal Alignment</label>
						<select id="horizontalalignment" name="horizontalalignment">
							<option value="Left">Left</option>
							<option value="Middle">Middle</option>
							<option value="Right">Right</option>
						</select>
					</div>
					<div class='settings-privacy section'>
						<label for="displaychatusername-enable">Display Chat Username</label>
						<div class="checkbox">
							<input id="displaychatusername-enable" name="displaychatusername" type="radio"/>
							<label for="displaychatusername-enable">Enable</label>
							<input id="displaychatusername-disable" name="displaychatusername" type="radio" />
							<label for="displaychatusername-disable">Disable</label>
						</div>
						<br/>
						<label for="guestlogindetails-optional">Guest Login Details</label>
						<div class="checkbox">
							<input id="guestlogindetails-required" name="guestlogindetails" type="radio"/>
							<label for="guestlogindetails-required">Required</label>
							<input id="guestlogindetails-optional" name="guestlogindetails" type="radio" />
							<label for="guestlogindetails-optional">Optional</label>
							<input id="guestlogindetails-disable" name="guestlogindetails" type="radio" />
							<label for="guestlogindetails-disable">Disable</label>
						</div>
						<br/>
						<label for="guestemailaddress-enable">Guest Email Address</label>
						<div class="checkbox">
							<input id="guestemailaddress-enable" name="guestemailaddress" type="radio"/>
							<label for="guestemailaddress-enable">Enable</label>
							<input id="guestemailaddress-disable" name="guestemailaddress" type="radio" />
							<label for="guestemailaddress-disable">Disable</label>
						</div>
						<br/>
						<label for="guestquestion-enable">Guest Question</label>
						<div class="checkbox">
							<input id="guestquestion-enable" name="guestquestion" type="radio"/>
							<label for="guestquestion-enable">Enable</label>
							<input id="guestquestion-disable" name="guestquestion" type="radio" />
							<label for="guestquestion-disable">Disable</label>
						</div>
						<br/>
						<label for="securitycode-enable">Security Code</label>
						<div class="checkbox">
							<input id="securitycode-enable" name="securitycode" type="radio"/>
							<label for="securitycode-enable">Enable</label>
							<input id="securitycode-disable" name="securitycode" type="radio" />
							<label for="securitycode-disable">Disable</label>
						</div>
						<br/>
						<label for="p3p">Privacy Preferences (P3P)</label>
						<input id="p3p" name="p3p" type="text" />
					</div>
				</div>
				<div class="settings-dialog" style="position: absolute; bottom: -90px; background: #e5e5e5; z-index: 1000; font-family:Segoe UI Light, Helvetica Neue, RobotoLight, Segoe UI, Segoe WP, sans-serif; width: 100%; height: 90px">
					<div style="position:absolute; bottom:25px; left:15px" class="progressring">
						<img src="<?php echo($server); ?>/images/ProgressRing.gif" alt="Loading" style="opacity: 0.5"/>
					</div>
					<div style="position:absolute; top:10px; left:50px">
						<div style="font-size: 24px; font-weight: 100; margin: 20px 0 10px 20px" class="dialog-title">Saving Settings</div>
						<div style="font-size: 16px; font-weight: 100; margin: 0 20px" class="dialog-description">One moment while your settings are saved.</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  </body>
</html>