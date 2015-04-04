<docroot
	orientation="{$orientation}"
	format='{$format}'
	unit="{$unit}"
	encoding="utf-8"
	
	author="{$author}"
	title="{$title}"
	subject="{$subject}"
	keyword="{$keyword}"

	margin-top="{$marginTop}"
	margin-left="{$marginLeft}"
	margin-right="{$marginRight}"
	margin-bottom="{$marginBottom}"
	margin-header="{$marginHeader}"
	margin-footer="{$marginFooter}"
	
	autopagebreak="{$autoPageBreak}" >
	
	{*here render document header*}
	<header>
		<row>
			<col width='17'>
				<row>
					<col>
						<img width='15' height='15' src='@img/logo.png' />
					</col>
				</row>
				<row>
					<col>
						<span text-size='4' text="{$comProfile.website}" />
					</col>
				</row>
			</col>
			<col>
				<row>
					<col width='73'>
						<span wrap-text='0' font-style='bold' text-size='16' text="{$comProfile.comName}" />
					</col>
					<col>
						<span wrap-text='0' height='5.8' v-align='bottom' text-size='6' text='( ROC : {$comProfile.regNo} )' />
					</col>
				</row>
				<row>
					<col>
						<span font-style='' text-size='9' text="{$comProfile.addr}" />
					</col>
				</row>
				<row>
					<col>
						<span text-size='8' text="TEL: {$comProfile.tel}" />
					</col>
				</row>
			</col>
		</row> 

		<row>
			<col><hr line-width='0.5' /></col>
		</row>

	</header>	

	{*here footer --- not support yet *}
	<footer>
		<row><col font-style="B" text-align="right" text="Page x of y"></col></row>
	</footer>
	
	{* This is document content *}
	<page>
		<row>
			<col><span text='asd' /></col>
			<col><span text='qwe' /></col>
		</row>
		
		<row>
			<col><span text='123' /></col>
			<col><span text-size='18' text='456' /></col>
			<col>
				<row>
					<col><span text='bnf' /></col>
					<col><span text='qpe' /></col>
					<col><span border='0.5 solid #000' text='ggj' /></col>
				</row>
				<span text='fgh' />
				<span text='nko' />
				<row>
					<col><span text='yuk' /></col>
					<col><span text='vkr' /></col>
				</row>
			</col>
		</row>
		
		<row>
			<col><span font-style='bold' text-size='18' text='nmi' /></col>
			<col><span text-color='#F00' text='scp' /></col>
		</row>
		
		<row>
			<col>
				<row>
					<col><span text="{$comProfile.comName} #-#" /></col>
					<col><span text="{$comProfile.comName} *-*" /></col>
				</row>
			</col>
		</row>
		
	</page>
</docroot>