(function(t){function e(e){for(var i,n,a=e[0],l=e[1],d=e[2],p=0,g=[];p<a.length;p++)n=a[p],s[n]&&g.push(s[n][0]),s[n]=0;for(i in l)Object.prototype.hasOwnProperty.call(l,i)&&(t[i]=l[i]);c&&c(e);while(g.length)g.shift()();return r.push.apply(r,d||[]),o()}function o(){for(var t,e=0;e<r.length;e++){for(var o=r[e],i=!0,a=1;a<o.length;a++){var l=o[a];0!==s[l]&&(i=!1)}i&&(r.splice(e--,1),t=n(n.s=o[0]))}return t}var i={},s={widget:0},r=[];function n(e){if(i[e])return i[e].exports;var o=i[e]={i:e,l:!1,exports:{}};return t[e].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=i,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"===typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(o,i,function(e){return t[e]}.bind(null,i));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="";var a=window["webpackJsonp"]=window["webpackJsonp"]||[],l=a.push.bind(a);a.push=e,a=a.slice();for(var d=0;d<a.length;d++)e(a[d]);var c=l;r.push([3,"chunk-vendors","chunk-common"]),o()})({3:function(t,e,o){t.exports=o("f70f")},"33ea":function(t,e,o){},f70f:function(t,e,o){"use strict";o.r(e);o("cadf"),o("551c"),o("097d");var i=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{class:t.mainClass},[o("widget-settings",{ref:"settings"}),o("widget-accordion"),t.fullWidth?o("div",{staticClass:"monsterinsights-fullwidth-mascot"}):t._e(),t.fullWidth?o("div",{staticClass:"monsterinsights-fullwidth-report-title",domProps:{textContent:t._s(t.text_overview_report)}}):t._e(),t.fullWidth?t._e():o("widget-footer")],1)},s=[],r=o("e814"),n=o.n(r),a=(o("7f7f"),o("cebc")),l=o("561c"),d=o("2f62"),c=o("c1df"),p=o.n(c),g=o("f284"),u=o("795b"),h=o.n(u),f=o("2b0e"),w=o("f499"),m=o.n(w),b=o("bc3a"),v=o.n(b),_=function(t,e,o,i){return new h.a(function(t){var s=new FormData;o=m()(o),s.append("action","monsterinsights_save_widget_state"),s.append("nonce",f["a"].prototype.$mi.nonce),s.append("width",e),s.append("reports",o),s.append("interval",i),v.a.post(f["a"].prototype.$mi.ajax,s).then(function(e){f["a"].prototype.$swal.close(),t(e.data)}).catch(function(t){console.log(t)})})},y={saveWidgetState:_},x=function(t){return new h.a(function(e){if(f["a"].prototype.$mi.widget_state&&f["a"].prototype.$mi.widget_state.reports)for(var o in f["a"].prototype.$mi.widget_state.reports)if(f["a"].prototype.$mi.widget_state.reports.hasOwnProperty(o))for(var i in f["a"].prototype.$mi.widget_state.reports[o])f["a"].prototype.$mi.widget_state.reports[o].hasOwnProperty(i)&&(t.state.reports[i].enabled=f["a"].prototype.$mi.widget_state.reports[o][i]);t.state.width=f["a"].prototype.$mi.widget_state.width,t.state.interval=f["a"].prototype.$mi.widget_state.interval,e(!0)})},O=function(t){var e={overview:{},publisher:{},ecommerce:{}},o=t.rootGetters.hasOwnProperty("$_reports/date")?t.rootGetters["$_reports/date"]["interval"]:"";for(var i in t.state.reports)if(t.state.reports.hasOwnProperty(i)&&t.state.reports[i].hasOwnProperty("type")){var s=t.state.reports[i].type;e[s][i]=t.state.reports[i]["enabled"]}y.saveWidgetState(t,t.state.width,e,o)},R={processDefaults:x,saveWidgetState:O},$=function(t){return t.reports},D=function(t){return t.width},j=function(t){return t.loaded},W=function(t){return t.error},k={reports:$,width:D,loaded:j,error:W},P=function(t,e){t.reports[e]&&f["a"].set(t.reports[e],"enabled",!0)},S=function(t,e){t.reports[e]&&f["a"].set(t.reports[e],"enabled",!1)},E=function(t,e){t.loaded=e},T=function(t,e){t.width=e},C=function(t,e){t.error=e},A={ENABLE_REPORT:P,DISABLE_REPORT:S,UPDATE_LOADED:E,UPDATE_WIDTH:T,SET_ERROR:C},L={width:"regular",interval:30,loaded:!1,reports:{overview:{type:"overview",name:Object(l["a"])("Overview Report","google-analytics-for-wordpress"),enabled:!0,component:"WidgetReportOverview"},toppages:{type:"overview",name:Object(l["a"])("Top Posts/Pages","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the most viewed posts and pages on your website.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportTopPosts"},newvsreturn:{type:"overview",name:Object(l["a"])("New vs. Returning Visitors","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This graph shows what percent of your user sessions come from new versus repeat visitors.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportNewVsReturning"},devices:{type:"overview",name:Object(l["a"])("Device Breakdown","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportDevices"},landingpages:{type:"publisher",name:Object(l["a"])("Top Landing Pages","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top pages users first land on when visiting your website.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportLandingPages"},exitpages:{type:"publisher",name:Object(l["a"])("Top Exit Pages","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top pages users exit your website from.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportExitPages"},outboundlinks:{type:"publisher",name:Object(l["a"])("Top Outbound Links","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top links clicked on your website that go to another website.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportOutboundLinks"},affiliatelinks:{type:"publisher",name:Object(l["a"])("Top Affiliate Links","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top affiliate links your visitors clicked on.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportAffiliateLinks"},downloadlinks:{type:"publisher",name:Object(l["a"])("Top Download Links","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the download links your visitors clicked the most.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportDownloadLinks"},infobox:{type:"ecommerce",name:Object(l["a"])("Overview","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportEcommerceOverview"},products:{type:"ecommerce",name:Object(l["a"])("Top Products","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top selling products on your website.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportTopProducts"},conversions:{type:"ecommerce",name:Object(l["a"])("Top Conversion Sources","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the top referral websites in terms of product revenue.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportTopConversions"},addremove:{type:"ecommerce",name:Object(l["a"])("Total Add/Remove","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportAddRemove"},days:{type:"ecommerce",name:Object(l["a"])("Time to Purchase","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows how many days from first visit it took users to purchase products from your site.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportDays"},sessions:{type:"ecommerce",name:Object(l["a"])("Sessions to Purchase","google-analytics-for-wordpress"),tooltip:Object(l["a"])("This list shows the number of sessions it took users before they purchased a product from your website.","google-analytics-for-wordpress"),enabled:!1,component:"WidgetReportSessions"}},error:!1},U={namespaced:!0,state:L,actions:R,getters:k,mutations:A},B=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"monsterinsights-widget-accordion monsterinsights-widget-accordion-lite"},t._l(t.widgetReports(),function(e,i){return o("div",{key:i,class:t.reportClass(i)},[t.showReportTitle(e.enabled)?o("div",{class:t.toggleClass(i),attrs:{tabindex:"0"},on:{click:function(e){e.preventDefault(),t.toggle(e,i)},keyup:[function(e){if(!("button"in e)&&t._k(e.keyCode,"space",32,e.key,[" ","Spacebar"]))return null;t.toggle(e,i)},function(e){if(!("button"in e)&&t._k(e.keyCode,"enter",13,e.key,"Enter"))return null;t.toggle(e,i)}]}},[o("h2",{staticClass:"monsterinsights-widget-report-title"},[o("span",{domProps:{textContent:t._s(e.name)}}),e.tooltip?o("settings-info-tooltip",{attrs:{content:e.tooltip}}):t._e()],1)]):t._e(),t.showReport(i,e)?o("div",{staticClass:"monsterinsights-widget-content"},[t.error?o("widget-report-error",{attrs:{error:t.error}}):t._e(),"overview"===i&&t.loaded?o("WidgetReportOverview"):t.loaded?o("report-upsell-overlay",{attrs:{report:e.type}}):o("div",{staticClass:"monsterinsights-widget-loading"})],1):t._e()])}),0)},F=[],I=o("a4bb"),M=o.n(I),N=(o("c5f6"),function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"monsterinsights-report-row monsterinsights-report-infobox-row"},[o("report-infobox",{attrs:{title:t.text_sessions,value:t.infoboxSessionsData.value,change:t.infoboxSessionsData.change,color:t.infoboxSessionsData.color,direction:t.infoboxSessionsData.direction,days:t.infoboxRange,tooltip:t.text_infobox_tooltip_sessions}}),o("report-infobox",{attrs:{title:t.text_pageviews,value:t.infoboxPageviewsData.value,change:t.infoboxPageviewsData.change,color:t.infoboxPageviewsData.color,direction:t.infoboxPageviewsData.direction,days:t.infoboxRange,tooltip:t.text_infobox_tooltip_pageviews}}),o("report-infobox",{attrs:{title:t.text_duration,value:t.infoboxDurationData.value,change:t.infoboxDurationData.change,color:t.infoboxDurationData.color,direction:t.infoboxDurationData.direction,days:t.infoboxRange,tooltip:t.text_infobox_tooltip_average}}),o("report-infobox",{attrs:{title:t.text_bounce,value:t.infoboxBounceData.value,change:t.infoboxBounceData.change,color:t.infoboxBounceData.color,direction:t.infoboxBounceData.direction,days:t.infoboxRange,tooltip:t.text_infobox_tooltip_bounce}})],1)}),V=[],z=(o("6b54"),o("87b3"),o("bd74")),Y={name:"WidgetReportOverview",components:{ReportInfobox:z["a"]},computed:Object(a["a"])({},Object(d["b"])({overview:"$_reports/overview",widget_width:"$_widget/width"}),{infoboxRange:function(){return this.overview.infobox&&this.overview.infobox.range?this.overview.infobox.range:0},infoboxSessionsData:function(){return this.infoboxData("sessions")},infoboxPageviewsData:function(){return this.infoboxData("pageviews")},infoboxDurationData:function(){return this.infoboxData("duration")},infoboxBounceData:function(){return this.infoboxData("bounce",!0)}}),data:function(){return{chartKey:0,current_tab:"sessions",text_sessions:Object(l["a"])("Sessions","google-analytics-for-wordpress"),text_sessions_tooltip:Object(l["d"])(Object(l["a"])("Unique %s Sessions","google-analytics-for-wordpress"),"<br />"),text_pageviews:Object(l["a"])("Pageviews","google-analytics-for-wordpress"),text_pageviews_tooltip:Object(l["d"])(Object(l["a"])("Unique %s Pageviews","google-analytics-for-wordpress"),"<br />"),text_infobox_tooltip_sessions:Object(l["a"])("A session is the browsing session of a single user to your site.","google-analytics-for-wordpress"),text_infobox_tooltip_pageviews:Object(l["a"])("A pageview is defined as a view of a page on your site that is being tracked by the Analytics tracking code. Each refresh of a page is also a new pageview.","google-analytics-for-wordpress"),text_infobox_tooltip_average:Object(l["a"])("Total duration of all sessions (in seconds) / number of sessions.","google-analytics-for-wordpress"),text_infobox_tooltip_bounce:Object(l["a"])("Percentage of single page visits (or web sessions). It is the number of visits in which a person leaves your website from the landing page without browsing any further.","google-analytics-for-wordpress"),text_duration:Object(l["a"])("Avg. Session Duration","google-analytics-for-wordpress"),text_bounce:Object(l["a"])("Bounce Rate","google-analytics-for-wordpress")}},methods:{infoboxData:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],o={};return this.overview.infobox&&this.overview.infobox[t]&&(o.change=this.overview.infobox[t]["prev"],o.value=this.overview.infobox[t]["value"].toString(),0===this.overview.infobox[t]["prev"]?o.direction="":this.overview.infobox[t]["prev"]>0?(o.direction="up",o.color="green"):(o.direction="down",o.color="red")),e&&("down"===o.direction?o.color="green":o.color="red"),o},forceRerender:function(){this.chartKey+=1}}},H=Y,q=o("2877"),G=Object(q["a"])(H,N,V,!1,null,null,null);G.options.__file="WidgetReportOverview-Lite.vue";var J=G.exports,K=o("93ec"),Q=o("d3fc"),X=o("48c7"),Z=!1,tt=!1,et=!1,ot={name:"WidgetAccordion",components:{ReportUpsellOverlay:X["a"],WidgetReportError:Q["a"],SettingsInfoTooltip:K["a"],WidgetReportOverview:J},props:{mobileWidth:{default:782,type:Number}},data:function(){return{activeReport:"overview",reportsWithUpsell:{},isMobile:!1}},computed:Object(a["a"])({},Object(d["b"])({widget_reports:"$_widget/reports",widget_width:"$_widget/width",loaded:"$_widget/loaded",error:"$_widget/error"}),{widgetFullWidth:function(){return"regular"!==this.widget_width}}),methods:{widgetReports:function(){var t={};for(var e in Z=!1,this.widget_reports)if(this.widget_reports.hasOwnProperty(e)&&this.widget_reports[e].enabled){if(this.widgetFullWidth&&"undefined"!==typeof this.reportsWithUpsell[e]){if(!1!==Z)continue;Z=!0}t[e]=this.widget_reports[e]}if(this.widgetFullWidth&&this.error){var o=M()(t),i=t[o[0]];t={},t[o[0]]=i}return t},maybeHideUpsell:function(t){this.reportsWithUpsell[t]=1,this.widgetFullWidth&&!et&&(et=!0,this.$forceUpdate())},toggle:function(t,e){var o=this.widget_reports[e].type,i=this;i.$store.commit("$_widget/UPDATE_LOADED",!1),i.$store.commit("$_widget/SET_ERROR",!1),this.$store.dispatch("$_reports/getReportData",o).then(function(){i.$store.commit("$_widget/UPDATE_LOADED",!0)}),this.activeReport=e===this.activeReport?"":e,""!==this.activeReport&&this.scrollIntoView(t.target)},toggleClass:function(t){var e="monsterinsights-widget-toggle";return this.activeReport===t&&(e+=" monsterinsights-widget-toggle-active"),e},showReport:function(t,e){return!(!this.widgetFullWidth||!e.enabled||this.isMobile)||this.activeReport===t},reportClass:function(t){return"monsterinsights-widget-report-element monsterinsights-widget-report-"+t},scrollIntoView:function(t){this.$nextTick(function(){var e=t.getBoundingClientRect();window.scrollTo({top:e.top-50+pageYOffset,left:0,behavior:"smooth"})})},showReportTitle:function(t){return!!this.widgetFullWidth||t},handleResize:function(){tt||(tt=!0,window.requestAnimationFrame?window.requestAnimationFrame(this.resizeCallback):setTimeout(this.resizeCallback,66))},resizeCallback:function(){this.isMobile=window.innerWidth<this.mobileWidth,tt=!1}},mounted:function(){var t=this;this.$store.dispatch("$_reports/getReportData","overview").then(function(){t.$store.commit("$_widget/UPDATE_LOADED",!0),t.$forceUpdate()}),window.addEventListener("resize",this.handleResize),this.handleResize()},beforeDestroy:function(){window.removeEventListener("resize",this.handleResize)}},it=ot,st=Object(q["a"])(it,B,F,!1,null,null,null);st.options.__file="WidgetAccordion-Lite.vue";var rt=st.exports,nt=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"monsterinsights-widget-settings"},[o("widget-settings-width",{ref:"width"}),o("widget-settings-reports")],1)},at=[],lt=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"monsterinsights-widget-dropdown"},[o("button",{staticClass:"monsterinsights-widget-cog",attrs:{type:"button"},on:{click:t.toggleDropdown}},[o("i",{staticClass:"monstericon-cog"})]),t.dropdownVisible?o("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:t.hideDropdown,expression:"hideDropdown"}],staticClass:"monsterinsights-widget-dropdown-content"},[o("span",{domProps:{textContent:t._s(t.text_settings_overview)}}),t._l(t.getReportSettings("overview"),function(e,i){return o("div",{key:i,staticClass:"monsterinsights-widget-setting "},[o("label",{class:e.enabled?"monsterinsights-checked":"",attrs:{tabindex:"0"},on:{click:function(e){e.preventDefault(),t.toggleReport(e,i)},keyup:[function(e){if(!("button"in e)&&t._k(e.keyCode,"enter",13,e.key,"Enter"))return null;t.toggleReport(e,i)},function(e){if(!("button"in e)&&t._k(e.keyCode,"space",32,e.key,[" ","Spacebar"]))return null;t.toggleReport(e,i)}]}},[o("input",{attrs:{type:"checkbox"},domProps:{checked:e.enabled}}),t._v(" "+t._s(e.name)+"\n\t\t\t")])])}),o("span",{domProps:{textContent:t._s(t.text_settings_publisher)}}),t._l(t.getReportSettings("publisher"),function(e,i){return o("div",{key:i,staticClass:"monsterinsights-widget-setting "},[o("label",{directives:[{name:"tooltip",rawName:"v-tooltip.left",value:t.tooltip_data,expression:"tooltip_data",modifiers:{left:!0}}],staticClass:"monsterinsights-faded",attrs:{tabindex:"0"}},[o("input",{attrs:{type:"checkbox"},domProps:{checked:e.enabled}}),t._v(" "+t._s(e.name)+"\n\t\t\t")])])}),o("span",{domProps:{textContent:t._s(t.text_settings_ecommerce)}}),t._l(t.getReportSettings("ecommerce"),function(e,i){return o("div",{key:i,staticClass:"monsterinsights-widget-setting "},[o("label",{directives:[{name:"tooltip",rawName:"v-tooltip.left",value:t.tooltip_data,expression:"tooltip_data",modifiers:{left:!0}}],staticClass:"monsterinsights-faded",attrs:{tabindex:"0"}},[o("input",{attrs:{type:"checkbox"},domProps:{checked:e.enabled}}),t._v(" "+t._s(e.name)+"\n\t\t\t")])])})],2):t._e()])},dt=[];f["a"].directive("click-outside",{bind:function(t,e,o){t.clickOutsideEvent=function(i){t===i.target||t.contains(i.target)||o.context[e.expression](i)},document.body.addEventListener("click",t.clickOutsideEvent)},unbind:function(t){document.body.removeEventListener("click",t.clickOutsideEvent)}});var ct={name:"WidgetSettingsReports",data:function(){return{dropdownVisible:!1,text_settings_overview:Object(l["a"])("Show Overview Reports","google-analytics-for-wordpress"),text_settings_publisher:Object(l["a"])("Show Publishers Reports","google-analytics-for-wordpress"),text_settings_ecommerce:Object(l["a"])("Show eCommerce Reports","google-analytics-for-wordpress"),tooltip_data:{content:Object(l["a"])("Available in PRO version","google-analytics-for-wordpress"),autoHide:!1,trigger:"hover focus click"}}},computed:Object(a["a"])({},Object(d["b"])({widget_reports:"$_widget/reports"}),{reportSettings:function(){var t={};for(var e in this.widget_reports)this.widget_reports.hasOwnProperty(e)&&"overview"!==e&&(t[e]=this.widget_reports[e]);return t}}),methods:{toggleReport:function(t,e){this.widget_reports[e].enabled?this.$store.commit("$_widget/DISABLE_REPORT",e):(this.$store.commit("$_widget/ENABLE_REPORT",e),this.fullWidth&&this.getReportData(e)),this.saveState()},getReportSettings:function(t){var e={};for(var o in this.reportSettings)this.reportSettings.hasOwnProperty(o)&&"overview"!==o&&t===this.reportSettings[o].type&&(e[o]=this.reportSettings[o]);return e},toggleDropdown:function(){this.dropdownVisible=!this.dropdownVisible},hideDropdown:function(){this.dropdownVisible=!1},saveState:function(){this.$store.dispatch("$_widget/saveWidgetState")}}},pt=ct,gt=Object(q["a"])(pt,lt,dt,!1,null,null,null);gt.options.__file="WidgetSettingsReports-Lite.vue";var ut=gt.exports,ht=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("button",{directives:[{name:"tooltip",rawName:"v-tooltip",value:t.tooltip_data,expression:"tooltip_data"}],staticClass:"monsterinsights-width-button",on:{click:t.toggleFullWidth}},[t.fullWidth?o("i",{staticClass:"monstericon-compress"}):o("i",{staticClass:"monstericon-expand"})])},ft=[],wt={name:"WidgetSettingsWidth",data:function(){return{normal_sortables:"",widget_element:"",welcome_panel:""}},computed:Object(a["a"])({},Object(d["b"])({widget_width:"$_widget/width"}),{fullWidth:{set:function(t){var e="regular";t&&(e="full"),this.$store.commit("$_widget/UPDATE_WIDTH",e),this.saveState()},get:function(){return"regular"!==this.widget_width}},tooltip_data:function(){return{content:this.fullWidth?Object(l["a"])("Show in widget mode","google-analytics-for-wordpress"):Object(l["a"])("Show in full-width mode","google-analytics-for-wordpress"),autoHide:!1,trigger:"hover focus click"}}}),methods:{toggleFullWidth:function(t){if(!0!==t&&(this.fullWidth=!this.fullWidth),this.fullWidth)this.widget_element.classList.add("monsterinsights-widget-full-width"),this.widget_element.classList.remove("monsterinsights-widget-regular-width"),this.welcome_panel.parentNode.insertBefore(this.widget_element,this.welcome_panel.nextSibling),this.getActiveReportsData();else{if(this.widget_element.classList.add("monsterinsights-widget-regular-width"),!0===t)return;this.widget_element.classList.remove("monsterinsights-widget-full-width"),this.normal_sortables.insertBefore(this.widget_element,this.normal_sortables.firstChild),this.normal_sortables.classList.remove("empty-container")}},getActiveReportsData:function(){var t=this,e={};for(var o in this.widget_reports)this.widget_reports.hasOwnProperty(o)&&this.widget_reports[o].enabled&&(e[this.widget_reports[o].type]=1);for(var i in e)e.hasOwnProperty(i)&&(t.$store.commit("$_widget/UPDATE_LOADED",!1),this.$store.dispatch("$_reports/getReportData",i).then(function(){t.$store.commit("$_widget/UPDATE_LOADED",!0)}))},saveState:function(){this.$store.dispatch("$_widget/saveWidgetState")}},mounted:function(){this.widget_element=document.getElementById("monsterinsights_reports_widget"),this.normal_sortables=document.getElementById("normal-sortables"),this.welcome_panel=document.getElementById("welcome-panel")}},mt=wt,bt=Object(q["a"])(mt,ht,ft,!1,null,null,null);bt.options.__file="WidgetSettingsWidth.vue";var vt=bt.exports,_t={name:"WidgetSettings",components:{WidgetSettingsWidth:vt,WidgetSettingsReports:ut},methods:{toggleFullWidth:function(){this.$refs.width.toggleFullWidth(!0)}}},yt=_t,xt=Object(q["a"])(yt,nt,at,!1,null,null,null);xt.options.__file="WidgetSettings-Lite.vue";var Ot=xt.exports,Rt=function(){var t=this,e=t.$createElement,o=t._self._c||e;return t.wpforms_enabled?t._e():o("div",{staticClass:"monsterinsights-widget-footer"},[o("span",{domProps:{innerHTML:t._s(t.text_recommended_plugin)}}),o("span",[t._v(" - ")]),o("a",{attrs:{href:t.wpforms_install},domProps:{textContent:t._s(t.text_action)}}),o("a",{attrs:{href:"https://wpforms.com/",target:"_blank"},domProps:{textContent:t._s(t.text_learn_more)}})])},$t=[],Dt={name:"WidgetFooter",data:function(){return{wpforms_enabled:this.$mi.wpforms_enabled,wpforms_install:this.$mi.wpforms_url,text_recommended_plugin:Object(l["d"])(Object(l["a"])("Recommended Plugin: %s","google-analytics-for-wordpress"),'<span class="monsterinsights-dark">WPForms</span>'),text_install:Object(l["a"])("Install","google-analytics-for-wordpress"),text_activate:Object(l["a"])("Activate","google-analytics-for-wordpress"),text_learn_more:Object(l["a"])("Learn More","google-analytics-for-wordpress")}},computed:{text_action:function(){return this.$mi.wpforms_installed?this.text_activate:this.text_install}}},jt=Dt,Wt=Object(q["a"])(jt,Rt,$t,!1,null,null,null);Wt.options.__file="WidgetFooter.vue";var kt=Wt.exports,Pt={name:"ModuleDashboardWidget",components:{WidgetFooter:kt,WidgetSettings:Ot,WidgetAccordion:rt},data:function(){return{text_overview_report:Object(l["a"])("Overview Report","google-analytics-for-wordpress")}},computed:Object(a["a"])({},Object(d["b"])({blocked:"$_app/blocked",blur:"$_reports/blur",widget_width:"$_widget/width"}),{route:function(){return this.$route.name},mainClass:function(){var t="monsterinsights-dashboard-widget-page";return this.blur&&(t+=" monsterinsights-blur"),t},fullWidth:function(){return"regular"!==this.widget_width}}),created:function(){var t="$_reports";t in this.$store._modules.root._children||this.$store.registerModule(t,g["a"]);var e="$_widget";if(e in this.$store._modules.root._children||this.$store.registerModule(e,U),this.$mi.widget_state&&this.$mi.widget_state.interval){var o=p()().subtract(1,"day"),i=p()(o).subtract(n()(this.$mi.widget_state.interval)-1,"day");this.$store.commit("$_reports/UPDATE_INTERVAL",this.$mi.widget_state.interval),this.$store.commit("$_reports/UPDATE_DATE",{start:i.format("YYYY-MM-DD"),end:o.format("YYYY-MM-DD")})}},mounted:function(){var t=this;this.$store.dispatch("$_widget/processDefaults").then(function(){t.$nextTick(function(){t.$refs.settings.toggleFullWidth(!0)})})}},St=Pt,Et=Object(q["a"])(St,i,s,!1,null,null,null);Et.options.__file="widget.vue";var Tt=Et.exports,Ct=o("8c4f"),At=o("619c"),Lt=o("7460"),Ut=o("1096"),Bt={install:function(t,e){var o=e.store;t.prototype.$mi_loading_toast=function(){},t.prototype.$mi_error_toast=function(t,e,i){o.commit("$_widget/SET_ERROR",{title:t,content:e,footer:i})}}},Ft=Bt,It=o("4360"),Mt=o("e37d"),Nt=o("6c6b"),Vt=(o("33ea"),document.getElementById("monsterinsights-dashboard-widget"));(f["a"].config.productionTip=!1,Vt)&&(Object(Nt["a"])({ctrl:!0}),f["a"].use(Ct["a"]),f["a"].use(At["a"]),f["a"].use(Mt["a"],{defaultTemplate:'<div class="monsterinsights-tooltip" role="tooltip"><div class="monsterinsights-tooltip-arrow"></div><div class="monsterinsights-tooltip-inner"></div></div>',defaultArrowSelector:".monsterinsights-tooltip-arrow, .monsterinsights-tooltip__arrow",defaultInnerSelector:".monsterinsights-tooltip-inner, .monsterinsights-tooltip__inner"}),f["a"].use(Lt["a"]),f["a"].use(Ut["a"]),f["a"].use(Ft,{store:It["a"]}),Object(l["c"])(window.monsterinsights.translations,"google-analytics-for-wordpress"),new f["a"]({store:It["a"],mounted:function(){It["a"].dispatch("$_app/init"),It["a"].dispatch("$_license/getLicense")},render:function(t){return t(Tt)}}).$mount(Vt))}});