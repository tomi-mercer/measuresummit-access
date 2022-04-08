<?php 
/**
 * Template Name: Dashboard
 */
 

get_header();


$dashboard_events = get_dashboard_events(get_the_id());
$events = $dashboard_events["events"];
#echo "<pre>";
	#print_r($events);
#echo "</pre>";
?>
<style type="text/css">
.btn-play{
	top:53% !important
}
.chatbox--inner{
	height:78vh !important;
}
</style>
<main class="dashboard">
	<div class="player">
		<div class="player--inner player-container">
			<div class="player--inner-wrapper">				
				<div class="player--main">
					<div class="player--main-inner">
						<button class="btn-play"><svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="50" cy="50" r="50" fill="#EBB129"/>
							<path d="M66.38 52.786L40.93 67.552C38.771 68.804 36 67.288 36 64.765V35.233c0-2.52 2.768-4.04 4.931-2.784L66.38 47.215a3.208 3.208 0 010 5.571z" fill="#fff"/>
							</svg>
						</button>					
						
						<div class="the-player">
							<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style>
							<div class='embed-container'>
								<?=get_field("video_player");?>
							</div>
						</div>

					</div>
				</div>

				<div class="player--navigation">
					<div class="player--nav prev presenting-now">
						
					</div>
					<div class="player--nav next presenting-next">
						
					</div>
				</div>
			</div>
		</div>
		<aside class="player--sidebar player-container"  x-data="{ tabsidebar: 'schedule' }">
			<div class="player--sidebar-ctrl">

				
				<button
					 :class="{ 'active': tabsidebar === 'schedule' }"  
					 @click="tabsidebar = 'schedule'">
				 	<svg width="20" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1v4M6 1v4M1 9h18M3 3h14a2 2 0 012 2v14a2 2 0 01-2 2H3a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				 </button>
				<button 
					:class="{ 'active': tabsidebar === 'livechat' }" 
					@click="tabsidebar = 'livechat'">
					<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 13a2 2 0 01-2 2H5l-4 4V3a2 2 0 012-2h14a2 2 0 012 2v10z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>

				<?php 
				if(get_field("show_q&a")[0]=="yes"){
				?>
				<button 
					:class="{ 'active': tabsidebar === 'ask' }" 
					@click="tabsidebar = 'ask'">
					<svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.778 7.836c.196-.52.583-.958 1.092-1.237a2.656 2.656 0 011.69-.288c.583.093 1.11.376 1.491.797.38.421.589.955.588 1.506 0 1.554-2.502 2.332-2.502 2.332m.067 3.11h.009" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 10.444a9.311 9.311 0 01-1 4.223 9.445 9.445 0 01-8.444 5.222 9.311 9.311 0 01-4.223-1L1 21l2.111-6.333a9.311 9.311 0 01-1-4.223A9.444 9.444 0 017.333 2a9.311 9.311 0 014.223-1h.555A9.422 9.422 0 0121 9.889v.556z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>
				<?php 
				}
				?>

			</div>
			<!-- schedule tab -->
			<div x-show="tabsidebar === 'schedule'" class="schedule" style="display:none;">
				<div class="tab-heading">					
					<h2>Schedule </h2>
					<button class="btn-close"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 1L1 13M1 1l12 12" stroke="#99A1A9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
				</div>

				<?php 
				$i = 0;
				foreach($events as $event){
					$i++;
					if($event["is_active_event"]){
						$activeTab = $i;
						
					}
				}
				?>
				<div x-data="{ tab: 'day<?=$activeTab?>' }" class="day--schedule">
					<div class="day--schedule-tabs">	
						<?php 
						$i = 0;
						foreach($events as $event){
							$i++;
						?>
					    	<button class="btn-day <?php if($event["is_active_event"]){
								echo "active";
								
								}?>" @click="tab = 'day<?=$i?>'">DAY <?=$i?></button>
						<?php 
						}
						?>	
					</div>

					<?php 
					$i = 0;
					foreach($events as $event){
						$i++;
					?>
					<div x-show="tab === 'day<?=$i?>'" class="day--schedule-details" style="<?php 
						if($event["is_active_event"]){
							echo "dispay:block";
						}
					?>">
				    	<h3><?=$event["day_title"];?></h3>
						<?php 
						foreach($event["speakers"] as $speaker){
						?>
				    	<div class="schedule--timeline">
				    		<div class="avatar"><img src="<?=$speaker["speaker"]["avatar"];?>" alt=""></div>
				    		<div class="schedule--details">
				    			<div class="time"><?=$speaker["time"];?></div>
				    			<div class="title"><?=$speaker["topic"];?></div>
				    			<div class="host">presented by <a target="_blank" href="<?=$speaker["speaker"]["speaker_link"];?>"><?=$speaker["speaker"]["name"];?></a></div>
				    		</div>
				    	</div>
						<?php 
						}
						?>
				    	
				    </div>
					<?php 
					}
					?>
				   
				</div>
			</div>
			<!-- end schedule tab -->

			<!-- live chat -->
			<div x-show="tabsidebar === 'livechat'"  class="livechat" style="display:none;">
				<div class="tab-heading">
					<h2>Live Chat</h2>
					<button class="btn-close"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 1L1 13M1 1l12 12" stroke="#99A1A9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
				</div>
<!-- 				<div class="watching-now">1,234 watching now  •  Started streaming 10 minutes ago</div> -->
				<div class="chatbox">
					<div class="chatbox--inner" style="height:75vh !important;overflow:hidden">

						<?php echo do_shortcode("[chatroll width='450' height='100%' id='6QdUitdlGXb' name='measuresummit' apikey='ctlhh3hxjjcgerxo' domain='chatroll.com']");?>


					</div>
				</div>
				<div class="comment" style="display: none;">
					<div class="comment--inner">
						<div class="avatar">
							<img src="<?=get_stylesheet_directory_uri();?>/assets/img/ahmad.jpg" alt="">
						</div>
						<div class="comment--input" style="position:relative">							
							<!-- <textarea name="comment" id="comment"  placeholder="Say Something"></textarea> -->

							

							<input type="text" name="comment" id="comment" placeholder="Say Something">
						</div>
					</div>
				</div>
			</div>
			<!-- end livechat -->
			<!-- ask a question-->
			<div x-show="tabsidebar === 'ask'" class="askaquestion" style="display:none;">
				<div class="tab-heading">					
					<h2>Ask A Question </h2>
					<button class="btn-close"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 1L1 13M1 1l12 12" stroke="#99A1A9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
				</div>
				<div class="questions questions-list">


				</div>
				<div class="question">
					<div class="question--inner">
						<div class="avatar">
							<img src="<?=get_avatar_url(get_current_user_id())?>" alt="">
						</div>
						<div class="question--input" style="position:relative">							
						
							<a type="button" onclick="jQuery('.hdn-submit-question').trigger('click');" style="position:absolute; background:none; right: 10px; top:15px; cursor:pointer; z-index:1000">
								<svg width="30" height="30" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M20 0C22.6264 0 25.2272 0.517315 27.6537 1.52241C30.0802 2.5275 32.285 4.00069 34.1421 5.85786C35.9993 7.71504 37.4725 9.91982 38.4776 12.3463C39.4827 14.7728 40 17.3736 40 20C40 25.3043 37.8929 30.3914 34.1421 34.1421C30.3914 37.8929 25.3043 40 20 40C17.3736 40 14.7728 39.4827 12.3463 38.4776C9.91982 37.4725 7.71504 35.9993 5.85786 34.1421C2.10714 30.3914 0 25.3043 0 20C0 14.6957 2.10714 9.60859 5.85786 5.85786C9.60859 2.10714 14.6957 0 20 0ZM12 11.42V18.1L26.28 20L12 21.9V28.58L32 20L12 11.42Z" fill="#58b649"/>
								</svg>
								
							</a>
							<form onsubmit="submitQuestion(); return false">
								<input type="text" name="question" id="question" placeholder="Ask a question" required>
								<button type="submit" class="hdn-submit-question" style="position:absolute;opacity:0; visibility:hidden"></button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- end ask a question -->
		</aside>
	</div>

	<div class="companies">
		<div class="companies--inner">
			<div class="heading">
				<h2>MeasureSummit 2021 Is Proudly Sponsored By:</h2>
			</div>
			<div class="companies-container">				
				<div class="companies--logo" style="width: 100%;text-align: center">
					<a href="https://measuresummit.com/access/booth/" target="_blank"><img src="https://measuresummit.com/access/wp-content/uploads/2021/09/Email-logos-rectangle.png" alt=""></a>
				</div>
				
			</div>
		</div>
	</div>

	

    <script>
		jQuery(".btn-day").click(function(){
		  jQuery('.active').removeClass('active'); // remove all current selections
			jQuery(this).addClass('active')            // select this element
		});
		
      // 2. This code loads the IFrame Player API code asynchronously.
	  let youtubeVideoId = "";
      var tag = document.createElement('script');
	  let player;
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      
      function onYouTubeIframeAPIReady() {
		if(youtubeVideoId !=""){
			player = new YT.Player('player', {
				height: '390',
				width: '640',
				videoId: youtubeVideoId,
				events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
				},
				playerVars: {
					showinfo: 0
				}
			});
		}
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {

			/* 
			-1 (unstarted)
			0 (ended)
			1 (playing)
			2 (paused)
			3 (buffering)
			5 (video cued).
			*/
			console.log(event.data);
			$(".btn-play").show();
			switch(event.data){
				case 1: 
					$(".btn-play").hide();
					break;

				case 2: case -1: case 5: case 3:
					$(".btn-play").show();
					break;
			}
			 

		
      }
      function stopVideo() {
        player.stopVideo();
      }

	  jQuery(document).on("click", ".btn-play", () => {
		player.playVideo();
	  });

	  let speakerPostId=0;

	  let loadingQuestions=false;
	  

	  jQuery(document).ready( $ => {

			$(document).on("click",".reply-question", e => {
				e.preventDefault();
				uuid = $(e.currentTarget).attr("data-uuid");
				console.log(uuid)

				$(e.currentTarget).parent().parent().find(".reply-form").toggle();
			});

			sidebarQuestionsAsks = () => {

				console.log("speakerPostId", speakerPostId);

				<?php 
				if(get_field("show_q&a")[0]=="yes"){
				?>
				$.ajax({
					url: "<?=admin_url("admin-ajax.php?action=speaker_questionslist")?>",
					data: {
						speaker_post_id: speakerPostId
					},
					dataType: "json",
					success: d => {
						$(".hdn-submit-question").removeAttr("disabled");
						loadingQuestions=false;
						// console.log("QUESTIONS ", d);
						tpl = "";
						d.map( q => {
							console.log("QUESTION ", q)
								replies = ``;

								q.answers.map( a => {
									replies += `
										<div style="margin:10px 0px">
											
											<div style="display:flex">
												<div class="avatar" style="width:24px;height: 31px;flex: 0 0 29px;margin-right: 10px;"><img src="${a.avatar}" /></div>
												<b>${a.name}</b>
											</div>
											<div style="padding-left:40px">${a.answer}</div>
										
										</div>
									`;
								})

								tpl += `
									<div class="questions-item">
										<div class="avatar">
											<img src="${q.avatar}" alt="">
										</div>
										<div class="questions-details">
											<h3 class="name">${q.name}</h3>
											<div class="metadata">${q.role}  •  ${q.datetime} - <a data-uuid="${q.uuid}" href="" class="reply-question">Reply</a></div>
											<div class="question-main">
												<p>${q.question}</p>
											</div>

											<div style="margin-left:10px">
												${replies}
											</div>

											<form onsubmit="submitReply(this); return false" class="reply-form" style="display:none; position:relative; margin-top:15px">
												<input type="hidden" name="uuid" value="${q.uuid}" />
												<input type="hidden" name="speaker_post_id" value="${speakerPostId}" />
												<input style="width:100%" type="text" name="reply" id="reply-${q.uuid}" placeholder="Write a reply" required>
												<button type="submit" class="hdn-submit-question-reply-${q.uuid}" style="position:absolute;opacity:0; visibility:hidden"></button>
												
												<a type="button" onclick="jQuery('.hdn-submit-question-reply-${q.uuid}').trigger('click');" 
												style="position:absolute; background:none; right: 10px; top:5px; cursor:pointer; z-index:1000">
													
													<svg width="30" height="30" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M20 0C22.6264 0 25.2272 0.517315 27.6537 1.52241C30.0802 2.5275 32.285 4.00069 34.1421 5.85786C35.9993 7.71504 37.4725 9.91982 38.4776 12.3463C39.4827 14.7728 40 17.3736 40 20C40 25.3043 37.8929 30.3914 34.1421 34.1421C30.3914 37.8929 25.3043 40 20 40C17.3736 40 14.7728 39.4827 12.3463 38.4776C9.91982 37.4725 7.71504 35.9993 5.85786 34.1421C2.10714 30.3914 0 25.3043 0 20C0 14.6957 2.10714 9.60859 5.85786 5.85786C9.60859 2.10714 14.6957 0 20 0ZM12 11.42V18.1L26.28 20L12 21.9V28.58L32 20L12 11.42Z" fill="#58b649"/>
													</svg>
													
												</a>
												
											</form>
											
										</div>
									</div>
								`;
						})

						$(".questions-list").html(tpl);
					}
				});

				<?php 
				}
				?>
			}

			submitReply = (e) => {
				$.ajax({
					url: "<?=admin_url("admin-ajax.php?action=submit_question_reply")?>",
					type: "post",
					data: $(e).serialize(),
					type : "post",
					beforeSend: () => {
						$(e).fadeTo("fast", .3);
					},
					success: () => {
						sidebarQuestionsAsks();
					}
				});
			}

			submitQuestion = () => {
				if(!loadingQuestions){
					$.ajax({
						url: "<?=admin_url("admin-ajax.php?action=submit_question")?>",
						type: "post",
						data: {
							speaker_post_id: speakerPostId,
							question: $("#question").val()
						},
						beforeSend: () => {
							$(".question--inner").fadeTo("fast",.3);
							$(".hdn-submit-question").attr("disabled");
							loadingQuestions=true;
						},
						success: () => {
							sidebarQuestionsAsks();
							$("#question").val("");
							$(".question--inner").fadeTo("fast",1);
							
						}
					});
				}else{
					console.log("SubmitQuestion Loading", loadingQuestions)
				}
				return false;
			}

		 
			$(document).on("keyup", "#question", event => {
				if( event.keyCode == 13){
					$(".hdn-submit-question").trigger("click");
					return false;
				}
			});
			

			loadPlayer = () => {
				$.ajax({
					url: "<?=admin_url("admin-ajax.php?action=dashboard_player");?>",
					dataType:"json",
					data: {
						dashboardid: <?=get_the_id()?>
					},
					beforeSend: () => {

					},
					success: (data) => {
						console.log("DATA", data);

						// .presenting-now
						console.log("Currenset Speaker PostId", speakerPostId);
						console.log("CURRENT SPEAKER",data.current_speaker);
						console.log("NEXT SPEAKER",data.next_speaker);

						presentingNow = "";
						presentingNext = "";
						$('.btn-play').hide();
						if(data.current_speaker){

							if(speakerPostId != data.current_speaker.speaker.post_id ){
								
								if(youtubeVideoId != data.current_video_id){
									youtubeVideoId = data.current_video_id;
									//$(".the-player").html(`<div id="player"></div>`);
								}
								
								setTimeout( ()=> {
									onYouTubeIframeAPIReady();
								}, 100);

								

							

								setTimeout( ()=> {
									speakerPostId = data.current_speaker.speaker.post_id;
									sidebarQuestionsAsks();
								}, 100);

								presentingNow = `
									<span>Presenting Now</span>
									<div class="user">
										<div class="avatar"><img src="${data.current_speaker.speaker.avatar}" /></div>
										<div class="topic">
											<h2 class="topic-title">${data.current_speaker.topic}</h2>
											<div class="topic-host">hosted by <a target="_blank" href="${data.current_speaker.speaker.speaker_link}">${data.current_speaker.speaker.name}</a>   |  
											<span class="time">${data.current_speaker.time}</span> </div>
										</div>
									</div>
								`;

								if(data.next_speaker){
									presentingNext = `
										<span>Up next</span>
										<div class="user">
											<div class="avatar"><img src="${data.next_speaker.speaker.avatar}" /></div>
											<div class="topic">
												<h2 class="topic-title">${data.next_speaker.topic}</h2>
												<div class="topic-host">hosted by <a target="_blank" href="${data.next_speaker.speaker.speaker_link}">${data.next_speaker.speaker.name}</a>   |  
												<span class="time">${data.next_speaker.time}</span> </div>
											</div>
										</div>
									`;
								}

								$(".presenting-next").html(presentingNext);
								$(".presenting-now").html(presentingNow);
							}
						}

						

					}
				});
			}

			setTimeout( () => {
				loadPlayer();
			}, 200);

			setInterval( () => {
				loadPlayer();
			}, 300000);  //This is the refresh interval on the dashboard. this is in miliseconds. eg. 1000 = 1 second

			 
	  });

    </script>
	
</main>


 <?php get_footer(); ?>