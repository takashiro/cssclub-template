<?php echo 'Theme by Joe';exit;?>
<script type="text/javascript">
var postminchars = parseInt('$_G['setting']['minpostsize']');
var postmaxchars = parseInt('$_G['setting']['maxpostsize']');
var disablepostctrl = parseInt('{$_G['group']['disablepostctrl']}');
var fid = parseInt('$_G[fid]');
</script>
<div id="f_pst" class="bm">
	<div class="bm_c">
		<form method="post" autocomplete="off" id="fastpostform" action="forum.php?mod=post&action=newthread&fid=$_G[fid]&topicsubmit=yes&infloat=yes&handlekey=fastnewpost" onSubmit="return fastpostvalidate(this)">
			<!--{hook/forumdisplay_fastpost_content}-->

			<div id="fastpostreturn" style="margin:-5px 0 5px"></div>

			<div class="pbt cl">
				<!--{if $_G['forum'][threadtypes][types]}-->
					<div class="ftid">
						<select name="typeid" id="typeid_fast" width="80">
						<option value="0" selected="selected">{lang select_thread_catgory}</option>
						<!--{loop $_G['forum'][threadtypes][types] $typeid $name}-->
							<!--{if empty($_G['forum']['threadtypes']['moderators'][$typeid]) || $_G['forum']['ismoderator']}-->
							<option value="$typeid"><!--{echo strip_tags($name);}--></option>
							<!--{/if}-->
						<!--{/loop}-->
						</select>
					</div>
					<script type="text/javascript" reload="1">simulateSelect('typeid_fast');</script>
				<!--{/if}-->
				<input type="text" id="subject" name="subject" class="px" value="" onkeyup="strLenCalc(this, 'checklen', 80);" tabindex="11" style="width: 25em" />
				<span>{lang comment_message1} <strong id="checklen">80</strong> {lang comment_message2}</span>
			</div>

			<div class="cl">
				<!--{if $_G[setting][fastsmilies]}--><div id="fastsmiliesdiv" class="y"><div id="fastsmiliesdiv_data"><div id="fastsmilies"></div></div></div><!--{/if}-->
				<div{if $_G[setting][fastsmilies]} class="hasfsl"{/if} id="fastposteditor">
					<div class="tedt">
						<div class="bar">
							<span class="y">
								<!--{hook/forumdisplay_fastpost_func_extra}-->
								<a href="forum.php?mod=post&action=newthread&fid=$_G[fid]" onclick="switchAdvanceMode(this.href);doane(event);">{lang post_advancemode}</a>
							</span>
							<!--{eval $seditor = array('fastpost', array('at', 'bold', 'color', 'img', 'link', 'quote', 'code', 'smilies'), !$allowfastpost ? 1 : 0, $allowpostattach && $allowfastpost ? '<span class="pipe z">|</span><span id="spanButtonPlaceholder">'.lang('template', 'upload').'</span>' : '');}-->
							<!--{hook/forumdisplay_fastpost_ctrl_extra}-->
							<!--{subtemplate common/seditor}-->
						</div>
						<div class="area">
							<!--{if $allowfastpost}-->
								<textarea rows="6" cols="80" name="message" id="fastpostmessage" onKeyDown="seditor_ctlent(event, '$(\'fastpostsubmit\').click()');" tabindex="12" class="pt"{eval echo getreplybg($_G['forum']['replybg']);}></textarea>
							<!--{else}-->
								<div class="pt hm">
									<!--{if !$_G['uid']}-->
										<!--{if !$_G['connectguest']}-->
											{lang login_to_post} <a href="member.php?mod=logging&action=login" onclick="showWindow('login', this.href)" class="xi2">{lang login}</a> | <a href="member.php?mod={$_G[setting][regname]}" class="xi2">$_G['setting']['reglinkname']</a>
										<!--{else}-->
											{lang connect_fill_profile_to_post}
										<!--{/if}-->
									<!--{else}-->
										{lang no_permission_to_post}<a href="javascript:;" onclick="$('newspecial').onclick()" class="xi2">{lang click_to_show_reason}</a>
									<!--{/if}-->
									<!--{hook/global_login_text}-->
								</div>
							<!--{/if}-->
						</div>
					</div>
				</div>
				<!--{if $fastpost && checkperm('seccode') && ($secqaacheck || $seccodecheck)}-->
					<!--{block sectpl}--><sec> <span id="sec<hash>" onclick="showMenu(this.id)"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div><!--{/block}-->
					<div class="mtm sec"><!--{subtemplate common/seccheck}--></div>
				<!--{/if}-->

				<input type="hidden" name="formhash" value="{FORMHASH}" />
				<input type="hidden" name="usesig" value="$usesigcheck" />
			</div>

			<!--{if $allowpostattach}-->
				<script type="text/javascript">
				var editorid = '';
				var ATTACHNUM = {'imageused':0,'imageunused':0,'attachused':0,'attachunused':0}, ATTACHUNUSEDAID = new Array(), IMGUNUSEDAID = new Array();
				</script>

				<input type="hidden" name="posttime" id="posttime" value="{TIMESTAMP}" />
				<div class="upfl{if empty($_GET[from]) && $_G[setting][fastsmilies]} hasfsl{/if}">
					<table cellpadding="0" cellspacing="0" border="0" width="100%" id="attach_tblheader" style="display: none">
						<tr>
							<td>{lang e_attach_insert}</td>
							<td class="atds">{lang description}</td>
							<!--{if $_G['group']['allowsetattachperm']}-->
							<td class="attv">
								{lang readperm}
								<img src="{IMGDIR}/faq.gif" alt="Tip" class="vm" onmouseover="showTip(this)" tip="{lang post_select_usergroup_readacces}" />
							</td>
							<!--{/if}-->
							<!--{if $_G['group']['maxprice']}--><td class="attpr">{$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]}</td><!--{/if}-->
							<td class="attc"></td>
						</tr>
					</table>
					<div class="fieldset flash" id="attachlist"></div>
					<!--{if empty($_G['setting']['pluginhooks']['forumdisplay_fastpost_upload_extend'])}-->
						<!--{subtemplate common/upload}-->
						<script type="text/javascript">
							var upload = new SWFUpload({
								upload_url: "{$_G[siteurl]}misc.php?mod=swfupload&action=swfupload&operation=upload&fid=$_G[fid]",
								post_params: {"uid" : "$_G[uid]", "hash":"$swfconfig[hash]"},
								file_size_limit : "$swfconfig[max]",
								file_types : "$swfconfig[attachexts][ext]",
								file_types_description : "$swfconfig[attachexts][depict]",
								file_upload_limit : $swfconfig['limit'],
								file_queue_limit : 0,
								swfupload_preload_handler : preLoad,
								swfupload_load_failed_handler : loadFailed,
								file_dialog_start_handler : fileDialogStart,
								file_queued_handler : fileQueued,
								file_queue_error_handler : fileQueueError,
								file_dialog_complete_handler : fileDialogComplete,
								upload_start_handler : uploadStart,
								upload_progress_handler : uploadProgress,
								upload_error_handler : uploadError,
								upload_success_handler : uploadSuccess,
								upload_complete_handler : uploadComplete,
								button_image_url : "{IMGDIR}/uploadbutton_small.png",
								button_placeholder_id : "spanButtonPlaceholder",
								button_width: 17,
								button_height: 25,
								button_cursor:SWFUpload.CURSOR.HAND,
								button_window_mode: "transparent",
								custom_settings : {
									progressTarget : "attachlist",
									uploadSource: 'forum',
									uploadType: 'attach',
									<!--{if $swfconfig['maxsizeperday']}-->
									maxSizePerDay: $swfconfig['maxsizeperday'],
									<!--{/if}-->
									<!--{if $swfconfig['maxattachnum']}-->
									maxAttachNum: $swfconfig['maxattachnum'],
									<!--{/if}-->
									uploadFrom: 'fastpost'
								},
								debug: false
							});
						</script>
					<!--{else}-->
						<!--{hook/forumdisplay_fastpost_upload_extend}-->
					<!--{/if}-->
				</div>
			<!--{/if}-->

			<p class="ptm pnpost">
				<a href="home.php?mod=spacecp&ac=credit&op=rule&fid=$_G[fid]" class="y" target="_blank">{lang post_credits_rule}</a>
				<button {if $fastpost}type="submit" {elseif !$_G['uid']}type="button" onclick="showWindow('login', 'member.php?mod=logging&action=login&guestmessage=yes')" {/if}name="topicsubmit" id="fastpostsubmit" value="topicsubmit" tabindex="13" class="pn pnc"><strong>{lang post_newthread}</strong></button>
				<!--{hook/forumdisplay_fastpost_btn_extra}-->
				<!--{if !empty($_G['setting']['pluginhooks']['forumdisplay_fastpost_sync_method'])}-->
					<span>
						{lang post_sync_method}:
						<!--{hook/forumdisplay_fastpost_sync_method}-->
					</span>
				<!--{/if}-->
				<!--{if helper_access::check_module('follow')}-->
				<label><input type="checkbox" name="adddynamic" class="pc" value="1" {if $_G['forum']['allowfeed'] && !$_G[tid] && empty($_G['forum']['viewperm'])}checked="checked"{/if} />{lang post_relay}</label>
				<!--{/if}-->
			</p>
		</form>
	</div>
</div>
