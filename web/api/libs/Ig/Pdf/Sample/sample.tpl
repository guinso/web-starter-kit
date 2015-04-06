<docroot
	orientation="P"
	format='A4'
	unit="mm"
	encoding="utf-8"
	
	author="Myreka"
	title="Purchase Order"
	subject="{$title}"
	keyword="Myreka, PO"

	margin-top="20"
	margin-left="20"
	margin-right="20"
	margin-bottom="10"
	margin-header="40"
	margin-footer="20"
	
	autopagebreak="1" >
	
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
			<col><hr line-width='0.5' padding-top='2' padding-bottom='2' /></col>
		</row>

	</header>	

	{*here render document header*}
	<footer>
		<row>
			<col><span text-align='right' text='F-067&#60;Rev:00&#62;' /></col>
		</row>
	</footer>
		
	{* This is document content *}
	<page>
		<row>
			<!-- Vendor info -->
			<col width='115'>
				<row>
					<col>
						<span font-style='bold' text-size='14' text='{$data.ven.name}' />
					</col>
				</row>
				<row>
					<col>
						<span width='55' text='{$data.ven.address}' />
					</col>
				</row>
				<row margin-top='1'>
					<col width='37'><span text='Tel :{$data.ven.tel}'></span></col>
					<col width='37'><span text='Fax :{$data.ven.fax}'></span></col>
				</row>
			</col>
			
			<!-- PO title -->
			<col width='55' 
				padding-top='2'
				padding-bottom='2'
				padding-left='2'
				padding-right='2'>
				<row>
					<col><span text='Purchase Order'
						text-align='center' 
						text-size='18'
						font-style='bold'
						padding-top='2'
						padding-bottom='2'
						border='0.3 solid #000' />
					</col>
				</row>
				<row margin-top='2'></row>
				<row margin-top='2'>
					<col width='10'><span text='No.' font-style='bold' /></col>
					<col width='16'><span text=':' font-style='bold' /></col>
					<col><span text='{$data.poNo}' 	font-style='bold' /></col>
				</row>
				<row margin-top='2'>
					<col width='10'><span text='Date' font-style='bold' /></col>
					<col width='16'><span text=':' font-style='bold' /></col>
					<col><span text='{function="date('d/m/Y', strtotime($data.date))"}' /></col>
				</row>
			</col>
		</row>
		<row><col><hr line-width='0.5' padding-top='1' padding-bottom='1' /></col></row>
		
		<!-- Payment Info -->
		<row>
			<col width='105'>
				<row>
					<col width='28'><span text='Supplier Code' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.venCode}' /></col>
				</row>
				<row>
					<col width='28'><span text='Contact Person' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.conName}' /></col>
				</row>
				<row>
					<col width='28'><span text='Supplier Ref. No.' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='N/A' /></col>
				</row>
			</col>
			
			<col>
				<row>
					<col width='26'><span text='Payment Terms' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.pt}' /></col>
				</row>
				<row>
					<col width='26'><span text='Currency' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.currency.currency}' /></col>
				</row>
				<row>
					<col width='26'><span text='Delivery Terms' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.dt}' /></col>
				</row>
				<row>
					<col width='26'><span text='Page No.' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='Page [[pgindex]] of [[pgsize]]' /></col>
				</row>
				<row>
					<col width='26'><span text='Category/Group' /></col>
					<col width='4'><span text=':' /></col>
					<col><span text='{$data.ven.sas[0].name}' /></col>
				</row>
			</col>
		</row>
	
		<!-- Table Items -->
		<row>
			<col width='20'><span border-top='0.5 solid #000' border-bottom='0.5 solid #000'  text='Item Code' text-align='left' /></col>
			<col><span padding-left='1' border-top='0.5 solid #000' border-bottom='0.5 solid #000'  text='Description' text-align='left' /></col>
			<col width='22'><span padding-left='2' border-top='0.5 solid #000' border-bottom='0.5 solid #000' text-align='right' text='Quantity' /></col>
			<col width='22'><span padding-left='2' border-top='0.5 solid #000' border-bottom='0.5 solid #000'  text='UOM' text-align='left' /></col>
			<col width='22'><span padding-left='2' border-top='0.5 solid #000' border-bottom='0.5 solid #000'  text='Unit Price' text-align='right' /></col>
			<col width='30'><span padding-left='2' border-top='0.5 solid #000' border-bottom='0.5 solid #000'  text='Amount' text-align='right' /></col>
		</row>
		{$totalQty = 0;}
		{$totalAmount = 0;}
		{loop="$data.items"}
		<row>
			{$totalQty += $value.qty;}
			{$totalAmount += $value.qty * $value.unitPrice;}
			<col width='20'><span  text='{$value.ipn.ipn}' text-align='left' /></col>
			<col>
				<span padding-left='1'  text='{$value.ipn.description}' text-align='left' />
				<span padding-left='1'  text='MPN: {$value.mpn.mpn}' text-align='left' />
			</col>
			<col width='22'><span padding-left='2' text-align='right' text='{$value.qty}' /></col>
			<col width='22'><span padding-left='2' text='{$value.uom}' text-align='left' /></col>
			<col width='22'><span padding-left='2' text='{$value.unitPrice}' text-align='right' /></col>
			<col width='30'><span padding-left='2' text='{$value.qty * $value.unitPrice}' text-align='right' /></col>
		</row>
		{/loop}
		
		<!-- End Table Items -->
	
		<!-- Start Signature Section -->
		<bottom>
			<row><col><hr line-width='0.5' padding-top='1' padding-bottom='1' /></col></row>
			<row>
				<col><span text='Total Quantity :' text-align='right' /></col>
				<col width='22'><span padding-left='2' text='{$totalQty}' text-align='right' /></col>
				<col width='44'><span padding-left='2' text='Total Amount({$data.currency.name}) :' text-align='right' /></col>
				<col width='30'><span text='{$totalAmount}' padding-left='2' border-bottom='0.5 solid #000' text-align='right' /></col>
			</row>
			<row>
				<col ><span height='30' wrap-text='0' /></col>
			</row>
			<row>
				<col width='58'><span text='Authorised Signature' border-top='0.4 solid #000' text-align='center' font-style='bold' /></col>
			</row>
		</bottom>
		<!-- End Signature Section -->
	</page>
		
</docroot>