[!
var pageAddress = KCN.App.page().get('Address');
var className = "";

var mdata = _(mdata).sortBy(function(pg){ return parseFloat(pg.Rb); });
var internal = _.filter(mdata, function(item){return item.Rb === "0"});
var menu = _.difference(mdata, internal);
var subMenu = _.filter(menu, function(item){ return item.Rb.indexOf('.') > -1;});
var menu = _.difference(menu, subMenu);
var sub = Array();
]


<div class="dropdown custom-dropdown pages-select menu-left dropdown-main">
	
	<a class="btn btn-large pages-select-link dropdown-toggle" data-toggle="dropdownnn">
		<i class="icon-file"></i><span>[[lng.menuPages]]</span>
	</a>

	<ul class="dropdown-menu left-dropdown pages-drop">
		<li class="non-sortable">
			<li class="dropdown-title">[[lng.menuPagesTitle]]</li>
		</li>
		<li class="divider non-sortable"></li>
		<ol class="sortableNested shown-pages">
			[! _(menu).each(function(page){ ]	
				[! className = (page.Address === pageAddress) ? "selected" : "";  ]
				<li id="list_[[page.Id]]" class="[[className]]">
					<div>
						<!--span class="sortHandler"></span-->
						<a class="[[className]]" data-page="[[page.Id]]">
						<!--i class="page-notvisible"></i-->
						[[page.Name]]</a>
							
						<a class="page-edit" data-uid="[[page.Id]]" title="Edit page">
							<i class="icon-cog" data-uid="[[page.Id]]"></i>
						</a>
					
					</div>
						[! sub = _.filter(subMenu, function(item){return item.Rb.match(new RegExp("^"+ page.Rb +"\\..*", "g")) !== null}) ;]
						[! if( sub.length > 0 ){ ]
							[! _(sub).each(function(page){ ]
								[! className = (page.Address === pageAddress) ? "selected" : "";  ]
								<ol class="submenu">
									<li id="list_[[page.Id]]" class="[[className]]">
										<div>
											<!--span class="sortHandler"></span-->
											<a class="[[className]]" data-page="[[page.Id]]">
											<!--i class="page-notvisible"></i-->
											[[page.Name]]</a>
												
											<a class="page-edit" data-uid="[[page.Id]]" title="Edit page">
												<i class="icon-cog" data-uid="[[page.Id]]"></i>
											</a>
										
										</div>
								</ol>
							[! }); ]
						[! } ]
				</li>
			[! }); ]
		</ol>
		[! if( internal.length > 0 ) { ]	
			<li class="divider non-sortable"></li>
			<li class="non-sortable">
				<li class="dropdown-title">[[lng.internalPages]]</li>
			</li>
		<li class="divider non-sortable"></li>
		<ol class="hiden-pages">
			[! } ]
			[! _(internal).each(function(page){ ]
				[! className = (page.Address === pageAddress) ? "selected" : "";  ]
				<li class="[[className]]">
					<!--span class="sortHandler"></span-->
					<a class="[[className]]" data-page="[[page.Id]]">
					
					<!--i class="page-notvisible icon-eye-close"></i-->
					
					[[page.Name]]</a>
						
					<a class="page-edit" data-uid="[[page.Id]]" title="Edit page">
						<i class="icon-cog" data-uid="[[page.Id]]"></i>
					</a>
				</li>
			[! }); ]
		</ol>
		<li class="divider non-sortable"></li>
		<li class="non-sortable">
			<a tabindex="-1" class="page-new non-sortable"><i class="icon-plus-sign"></i>[[lng.menuPagesNP]]</a>
		</li>
	</ul>

</div>
