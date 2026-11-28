<div class="p-3 lg:py-6 lg:px-0">

					<!-- Header -->
					<div class="flex flex-wrap items-center justify-between gap-3 mb-3 lg:mb-6">
						<h1 class="text-gray-900 text-xl max-lg:text-lg font-bold mb-0">Inventory Dashboard</h1>
						<div class="flex items-center gap-2 flex-wrap">
							<div class="relative rangepicker-input w-[174px] h-[28px] leading-none">
								<span class="absolute inset-y-0 left-0 flex items-center px-3 text-muted-foreground text-dark text-xs!">
									<i class="icon-calendar"></i>
								</span>
								<input type="text" class="form-input text-xs! h-[28px] inline-block w-full bg-light border-border-color rounded-md focus:ring-0 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:border-border-color pl-8! ps-8! flatpickr-input" data-provider="flatpickr" data-date-format="d M y" data-range-date="true" value="01 Jan 26 to 20 Jan 26" id="picker" readonly="readonly">
							</div>
							<div class="hs-dropdown [--placement:bottom-right] [--auto-close:inside] relative inline-flex">
                                <button type="button" class="hs-dropdown-toggle cursor-pointer btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-2 hover:bg-primary hover:border-primary hover:text-white focus:bg-primary focus:border-primary focus:text-white  focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    <i class="icon-download font-normal"></i>  Export <i class="icon-chevron-down"></i>
                                </button>

                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-50 bg-white border border-border-color shadow rounded-md mt-2 z-1" role="menu" aria-orientation="vertical" tabindex="-1">
                                    <div class="p-2 space-y-1"> 
                                        <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                            Export as PDF
                                        </a>
                                        <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                            Export as Excel
                                        </a>  
                                    </div>
                                </div>
                            </div>
							<a href="#" class="btn-sm bg-dark text-white border border-dark inline-flex items-center gap-2 hover:bg-primary-hover hover:border-primary-hover cursor-pointer" data-hs-overlay="#add-inventory-modal" aria-expanded="false"><i class="ph ph-plus"></i> Add Inventory</a>
						</div>
					</div>

					<!-- Category Distribution -->
					<div class="grid grid-cols-1 xxl:grid-cols-12 lg:grid-cols-12 md:grid-cols-12 gap-3 mb-3 w-full">

						<!-- Sparkline Cards -->
						<div class="flex flex-col gap-3 xxl:col-span-3 lg:col-span-6 md:col-span-6 min-w-0">
							<div class="bg-white border border-border-color rounded-md p-4 relative overflow-hidden flex-1">
								<div class="flex items-start justify-between mb-2">
									<div>
										<p class="text-xs text-default mb-1">Total Stock</p>
										<div class="flex items-center gap-2">
											<h2 class="text-2xl max-lg:text-xl font-bold text-gray-900 mb-0">250</h2>
											<span class="text-[11px] font-medium bg-success-transparent text-success px-1.5 py-0.5 rounded">+6.43%</span>
										</div>
									</div>
									<div class="size-9 rounded-md bg-success flex items-center justify-center shrink-0">
										<i class="ph-duotone ph-cube text-white"></i>
									</div>
								</div>
								<div id="inv-total-stock-spark" class="w-full" style="min-height: 80px;"><div id="apexchartsyoslne97" class="apexcharts-canvas apexchartsyoslne97 apexcharts-theme-light" style="width: 437px; height: 80px;"><svg xmlns="http://www.w3.org/2000/svg" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="437" height="80" role="application" aria-label="area chart with 1 data series: series-1" tabindex="0"><foreignObject x="0" y="0" width="437" height="80"><style type="text/css">.apexcharts-flip-y {
  transform: scaleY(-1) translateY(-100%);
  transform-origin: top;
  transform-box: fill-box;
}
.apexcharts-flip-x {
  transform: scaleX(-1);
  transform-origin: center;
  transform-box: fill-box;
}
.apexcharts-legend {
  display: flex;
  overflow: auto;
  padding: 0 10px;
}
.apexcharts-legend.apexcharts-legend-group-horizontal {
  flex-direction: column;
}
.apexcharts-legend-group {
  display: flex;
}
.apexcharts-legend-group-vertical {
  flex-direction: column-reverse;
}
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
  flex-wrap: wrap
}
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  flex-direction: column;
  bottom: 0;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  justify-content: flex-start;
  align-items: flex-start;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
  justify-content: center;
  align-items: center;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
  justify-content: flex-end;
  align-items: flex-end;
}
.apexcharts-legend-series {
  cursor: pointer;
  line-height: normal;
  display: flex;
  align-items: center;
}
.apexcharts-legend-text {
  position: relative;
  font-size: 14px;
}
.apexcharts-legend-text *, .apexcharts-legend-marker * {
  pointer-events: none;
}
.apexcharts-legend-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-right: 1px;
}

.apexcharts-legend-series.apexcharts-no-click {
  cursor: auto;
}
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
  display: none !important;
}
.apexcharts-inactive-legend {
  opacity: 0.45;
} </style></foreignObject><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g class="apexcharts-inner apexcharts-graphical" transform="translate(0, 1)"><defs><clipPath id="gridRectMaskyoslne97"><rect x="-3" y="-3" width="443" height="84" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskyoslne97"><rect x="-3" y="-3" width="443" height="84" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskyoslne97"><rect x="-3" y="0" width="443" height="78" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskyoslne97"></clipPath><clipPath id="nonForecastMaskyoslne97"></clipPath><linearGradient id="SvgjsGradient13" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(5,150,105,0.5)" stop-opacity="0.5"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop></linearGradient></defs><line x1="0" y1="0" x2="0" y2="78" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="78" fill="#B1B9C4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal" style="display: none;"><line x1="0" y1="0" x2="437" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="39" x2="437" y2="39" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="78" x2="437" y2="78" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical" style="display: none;"></g><line x1="0" y1="78" x2="437" y2="78" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="78" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders" style="display: none;"></g><g class="apexcharts-area-series apexcharts-plot-series"><g class="apexcharts-series" zIndex="0" seriesName="series-1" data:longestSeries="true" rel="1" data:realIndex="0"><path d="M 0 54.599999999999994C 13.904545454545454 54.599999999999994 25.82272727272727 42.9 39.72727272727273 42.9C 53.63181818181818 42.9 65.55 46.8 79.45454545454545 46.8C 93.35909090909091 46.8 105.27727272727273 35.099999999999994 119.18181818181819 35.099999999999994C 133.08636363636364 35.099999999999994 145.00454545454545 27.299999999999997 158.9090909090909 27.299999999999997C 172.81363636363636 27.299999999999997 184.7318181818182 37.05 198.63636363636365 37.05C 212.5409090909091 37.05 224.45909090909092 23.4 238.36363636363637 23.4C 252.26818181818183 23.4 264.18636363636364 15.599999999999994 278.0909090909091 15.599999999999994C 291.99545454545455 15.599999999999994 303.9136363636364 25.349999999999994 317.8181818181818 25.349999999999994C 331.7227272727273 25.349999999999994 343.6409090909091 11.699999999999989 357.54545454545456 11.699999999999989C 371.45000000000005 11.699999999999989 383.3681818181818 19.499999999999993 397.2727272727273 19.499999999999993C 411.17727272727274 19.499999999999993 423.0954545454546 3.8999999999999915 437 3.8999999999999915C 437 3.8999999999999915 437 3.8999999999999915 437 78 L 0 78z" fill="url(#SvgjsGradient13)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskyoslne97)" pathTo="M 0 54.599999999999994C 13.904545454545454 54.599999999999994 25.82272727272727 42.9 39.72727272727273 42.9C 53.63181818181818 42.9 65.55 46.8 79.45454545454545 46.8C 93.35909090909091 46.8 105.27727272727273 35.099999999999994 119.18181818181819 35.099999999999994C 133.08636363636364 35.099999999999994 145.00454545454545 27.299999999999997 158.9090909090909 27.299999999999997C 172.81363636363636 27.299999999999997 184.7318181818182 37.05 198.63636363636365 37.05C 212.5409090909091 37.05 224.45909090909092 23.4 238.36363636363637 23.4C 252.26818181818183 23.4 264.18636363636364 15.599999999999994 278.0909090909091 15.599999999999994C 291.99545454545455 15.599999999999994 303.9136363636364 25.349999999999994 317.8181818181818 25.349999999999994C 331.7227272727273 25.349999999999994 343.6409090909091 11.699999999999989 357.54545454545456 11.699999999999989C 371.45000000000005 11.699999999999989 383.3681818181818 19.499999999999993 397.2727272727273 19.499999999999993C 411.17727272727274 19.499999999999993 423.0954545454546 3.8999999999999915 437 3.8999999999999915C 437 3.8999999999999915 437 3.8999999999999915 437 78 L 0 78z" pathFrom="M 0 78 L 0 78 L 39.72727272727273 78 L 79.45454545454545 78 L 119.18181818181819 78 L 158.9090909090909 78 L 198.63636363636365 78 L 238.36363636363637 78 L 278.0909090909091 78 L 317.8181818181818 78 L 357.54545454545456 78 L 397.2727272727273 78 L 437 78z"></path><path d="M 0 54.599999999999994C 13.904545454545454 54.599999999999994 25.82272727272727 42.9 39.72727272727273 42.9C 53.63181818181818 42.9 65.55 46.8 79.45454545454545 46.8C 93.35909090909091 46.8 105.27727272727273 35.099999999999994 119.18181818181819 35.099999999999994C 133.08636363636364 35.099999999999994 145.00454545454545 27.299999999999997 158.9090909090909 27.299999999999997C 172.81363636363636 27.299999999999997 184.7318181818182 37.05 198.63636363636365 37.05C 212.5409090909091 37.05 224.45909090909092 23.4 238.36363636363637 23.4C 252.26818181818183 23.4 264.18636363636364 15.599999999999994 278.0909090909091 15.599999999999994C 291.99545454545455 15.599999999999994 303.9136363636364 25.349999999999994 317.8181818181818 25.349999999999994C 331.7227272727273 25.349999999999994 343.6409090909091 11.699999999999989 357.54545454545456 11.699999999999989C 371.45000000000005 11.699999999999989 383.3681818181818 19.499999999999993 397.2727272727273 19.499999999999993C 411.17727272727274 19.499999999999993 423.0954545454546 3.8999999999999915 437 3.8999999999999915" fill="none" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskyoslne97)" pathTo="M 0 54.599999999999994C 13.904545454545454 54.599999999999994 25.82272727272727 42.9 39.72727272727273 42.9C 53.63181818181818 42.9 65.55 46.8 79.45454545454545 46.8C 93.35909090909091 46.8 105.27727272727273 35.099999999999994 119.18181818181819 35.099999999999994C 133.08636363636364 35.099999999999994 145.00454545454545 27.299999999999997 158.9090909090909 27.299999999999997C 172.81363636363636 27.299999999999997 184.7318181818182 37.05 198.63636363636365 37.05C 212.5409090909091 37.05 224.45909090909092 23.4 238.36363636363637 23.4C 252.26818181818183 23.4 264.18636363636364 15.599999999999994 278.0909090909091 15.599999999999994C 291.99545454545455 15.599999999999994 303.9136363636364 25.349999999999994 317.8181818181818 25.349999999999994C 331.7227272727273 25.349999999999994 343.6409090909091 11.699999999999989 357.54545454545456 11.699999999999989C 371.45000000000005 11.699999999999989 383.3681818181818 19.499999999999993 397.2727272727273 19.499999999999993C 411.17727272727274 19.499999999999993 423.0954545454546 3.8999999999999915 437 3.8999999999999915" pathFrom="M 0 78 L 0 78 L 39.72727272727273 78 L 79.45454545454545 78 L 119.18181818181819 78 L 158.9090909090909 78 L 198.63636363636365 78 L 238.36363636363637 78 L 278.0909090909091 78 L 317.8181818181818 78 L 357.54545454545456 78 L 397.2727272727273 78 L 437 78" fill-rule="evenodd"></path><g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g class="apexcharts-datalabels" data:realIndex="0"></g></g><line x1="0" y1="0" x2="437" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="437" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-xaxis" transform="translate(0, 0)"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-legend" style="max-height: 40px;"></div><div class="apexcharts-sr-status" role="status" aria-live="polite" aria-atomic="true"></div></div></div>
							</div>
							<div class="bg-white border border-border-color rounded-md p-4 relative overflow-hidden flex-1">
								<div class="flex items-start justify-between mb-2">
									<div>
										<p class="text-xs text-default mb-1">Inventory Value</p>
										<div class="flex items-center gap-2">
											<h2 class="text-2xl max-lg:text-xl font-bold text-gray-900 mb-0">$2,300</h2>
											<span class="text-[11px] font-medium bg-danger-transparent text-danger px-1.5 py-0.5 rounded">-3.72%</span>
										</div>
									</div>
									<div class="size-9 rounded-md bg-orange flex items-center justify-center shrink-0">
										<i class="ph-duotone ph-currency-circle-dollar text-white"></i>
									</div>
								</div>
								<div id="inv-value-spark" class="w-full" style="min-height: 80px;"><div id="apexchartsf9mh8fuli" class="apexcharts-canvas apexchartsf9mh8fuli apexcharts-theme-light" style="width: 437px; height: 80px;"><svg xmlns="http://www.w3.org/2000/svg" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="437" height="80" role="application" aria-label="area chart with 1 data series: series-1" tabindex="0"><foreignObject x="0" y="0" width="437" height="80"><style type="text/css">.apexcharts-flip-y {
  transform: scaleY(-1) translateY(-100%);
  transform-origin: top;
  transform-box: fill-box;
}
.apexcharts-flip-x {
  transform: scaleX(-1);
  transform-origin: center;
  transform-box: fill-box;
}
.apexcharts-legend {
  display: flex;
  overflow: auto;
  padding: 0 10px;
}
.apexcharts-legend.apexcharts-legend-group-horizontal {
  flex-direction: column;
}
.apexcharts-legend-group {
  display: flex;
}
.apexcharts-legend-group-vertical {
  flex-direction: column-reverse;
}
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
  flex-wrap: wrap
}
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  flex-direction: column;
  bottom: 0;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  justify-content: flex-start;
  align-items: flex-start;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
  justify-content: center;
  align-items: center;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
  justify-content: flex-end;
  align-items: flex-end;
}
.apexcharts-legend-series {
  cursor: pointer;
  line-height: normal;
  display: flex;
  align-items: center;
}
.apexcharts-legend-text {
  position: relative;
  font-size: 14px;
}
.apexcharts-legend-text *, .apexcharts-legend-marker * {
  pointer-events: none;
}
.apexcharts-legend-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-right: 1px;
}

.apexcharts-legend-series.apexcharts-no-click {
  cursor: auto;
}
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
  display: none !important;
}
.apexcharts-inactive-legend {
  opacity: 0.45;
} </style></foreignObject><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g class="apexcharts-inner apexcharts-graphical" transform="translate(0, 1)"><defs><clipPath id="gridRectMaskf9mh8fuli"><rect x="-3" y="-3" width="443" height="84" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskf9mh8fuli"><rect x="-3" y="-3" width="443" height="84" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskf9mh8fuli"><rect x="-3" y="0" width="443" height="78" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskf9mh8fuli"></clipPath><clipPath id="nonForecastMaskf9mh8fuli"></clipPath><linearGradient id="SvgjsGradient14" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(230,81,0,0.5)" stop-opacity="0.5"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop></linearGradient></defs><line x1="0" y1="0" x2="0" y2="78" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="78" fill="#B1B9C4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal" style="display: none;"><line x1="0" y1="0" x2="437" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="39" x2="437" y2="39" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="78" x2="437" y2="78" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical" style="display: none;"></g><line x1="0" y1="78" x2="437" y2="78" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="78" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders" style="display: none;"></g><g class="apexcharts-area-series apexcharts-plot-series"><g class="apexcharts-series" zIndex="0" seriesName="series-1" data:longestSeries="true" rel="1" data:realIndex="0"><path d="M 0 70.19999999999999C 13.904545454545454 70.19999999999999 25.82272727272727 46.8 39.72727272727273 46.8C 53.63181818181818 46.8 65.55 58.499999999999986 79.45454545454545 58.499999999999986C 93.35909090909091 58.499999999999986 105.27727272727273 31.19999999999999 119.18181818181819 31.19999999999999C 133.08636363636364 31.19999999999999 145.00454545454545 38.999999999999986 158.9090909090909 38.999999999999986C 172.81363636363636 38.999999999999986 184.7318181818182 15.599999999999994 198.63636363636365 15.599999999999994C 212.5409090909091 15.599999999999994 224.45909090909092 46.8 238.36363636363637 46.8C 252.26818181818183 46.8 264.18636363636364 70.19999999999999 278.0909090909091 70.19999999999999C 291.99545454545455 70.19999999999999 303.9136363636364 38.999999999999986 317.8181818181818 38.999999999999986C 331.7227272727273 38.999999999999986 343.6409090909091 62.39999999999999 357.54545454545456 62.39999999999999C 371.45000000000005 62.39999999999999 383.3681818181818 46.8 397.2727272727273 46.8C 411.17727272727274 46.8 423.0954545454546 54.599999999999994 437 54.599999999999994C 437 54.599999999999994 437 54.599999999999994 437 78 L 0 78z" fill="url(#SvgjsGradient14)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskf9mh8fuli)" pathTo="M 0 70.19999999999999C 13.904545454545454 70.19999999999999 25.82272727272727 46.8 39.72727272727273 46.8C 53.63181818181818 46.8 65.55 58.499999999999986 79.45454545454545 58.499999999999986C 93.35909090909091 58.499999999999986 105.27727272727273 31.19999999999999 119.18181818181819 31.19999999999999C 133.08636363636364 31.19999999999999 145.00454545454545 38.999999999999986 158.9090909090909 38.999999999999986C 172.81363636363636 38.999999999999986 184.7318181818182 15.599999999999994 198.63636363636365 15.599999999999994C 212.5409090909091 15.599999999999994 224.45909090909092 46.8 238.36363636363637 46.8C 252.26818181818183 46.8 264.18636363636364 70.19999999999999 278.0909090909091 70.19999999999999C 291.99545454545455 70.19999999999999 303.9136363636364 38.999999999999986 317.8181818181818 38.999999999999986C 331.7227272727273 38.999999999999986 343.6409090909091 62.39999999999999 357.54545454545456 62.39999999999999C 371.45000000000005 62.39999999999999 383.3681818181818 46.8 397.2727272727273 46.8C 411.17727272727274 46.8 423.0954545454546 54.599999999999994 437 54.599999999999994C 437 54.599999999999994 437 54.599999999999994 437 78 L 0 78z" pathFrom="M 0 78 L 0 78 L 39.72727272727273 78 L 79.45454545454545 78 L 119.18181818181819 78 L 158.9090909090909 78 L 198.63636363636365 78 L 238.36363636363637 78 L 278.0909090909091 78 L 317.8181818181818 78 L 357.54545454545456 78 L 397.2727272727273 78 L 437 78z"></path><path d="M 0 70.19999999999999C 13.904545454545454 70.19999999999999 25.82272727272727 46.8 39.72727272727273 46.8C 53.63181818181818 46.8 65.55 58.499999999999986 79.45454545454545 58.499999999999986C 93.35909090909091 58.499999999999986 105.27727272727273 31.19999999999999 119.18181818181819 31.19999999999999C 133.08636363636364 31.19999999999999 145.00454545454545 38.999999999999986 158.9090909090909 38.999999999999986C 172.81363636363636 38.999999999999986 184.7318181818182 15.599999999999994 198.63636363636365 15.599999999999994C 212.5409090909091 15.599999999999994 224.45909090909092 46.8 238.36363636363637 46.8C 252.26818181818183 46.8 264.18636363636364 70.19999999999999 278.0909090909091 70.19999999999999C 291.99545454545455 70.19999999999999 303.9136363636364 38.999999999999986 317.8181818181818 38.999999999999986C 331.7227272727273 38.999999999999986 343.6409090909091 62.39999999999999 357.54545454545456 62.39999999999999C 371.45000000000005 62.39999999999999 383.3681818181818 46.8 397.2727272727273 46.8C 411.17727272727274 46.8 423.0954545454546 54.599999999999994 437 54.599999999999994" fill="none" fill-opacity="1" stroke="#e65100" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskf9mh8fuli)" pathTo="M 0 70.19999999999999C 13.904545454545454 70.19999999999999 25.82272727272727 46.8 39.72727272727273 46.8C 53.63181818181818 46.8 65.55 58.499999999999986 79.45454545454545 58.499999999999986C 93.35909090909091 58.499999999999986 105.27727272727273 31.19999999999999 119.18181818181819 31.19999999999999C 133.08636363636364 31.19999999999999 145.00454545454545 38.999999999999986 158.9090909090909 38.999999999999986C 172.81363636363636 38.999999999999986 184.7318181818182 15.599999999999994 198.63636363636365 15.599999999999994C 212.5409090909091 15.599999999999994 224.45909090909092 46.8 238.36363636363637 46.8C 252.26818181818183 46.8 264.18636363636364 70.19999999999999 278.0909090909091 70.19999999999999C 291.99545454545455 70.19999999999999 303.9136363636364 38.999999999999986 317.8181818181818 38.999999999999986C 331.7227272727273 38.999999999999986 343.6409090909091 62.39999999999999 357.54545454545456 62.39999999999999C 371.45000000000005 62.39999999999999 383.3681818181818 46.8 397.2727272727273 46.8C 411.17727272727274 46.8 423.0954545454546 54.599999999999994 437 54.599999999999994" pathFrom="M 0 78 L 0 78 L 39.72727272727273 78 L 79.45454545454545 78 L 119.18181818181819 78 L 158.9090909090909 78 L 198.63636363636365 78 L 238.36363636363637 78 L 278.0909090909091 78 L 317.8181818181818 78 L 357.54545454545456 78 L 397.2727272727273 78 L 437 78" fill-rule="evenodd"></path><g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g class="apexcharts-datalabels" data:realIndex="0"></g></g><line x1="0" y1="0" x2="437" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="437" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-xaxis" transform="translate(0, 0)"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-legend" style="max-height: 40px;"></div><div class="apexcharts-sr-status" role="status" aria-live="polite" aria-atomic="true"></div></div></div>
							</div>
						</div>

						<!-- Category Distribution -->
						<div class="bg-white border border-border-color rounded-md p-4 xxl:col-span-4 lg:col-span-6 md:col-span-6 min-w-0">
							<div class="flex items-center justify-between mb-2">
								<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Category Distribution</h3>
								<div class="hs-dropdown [--placement:bottom-right] [--auto-close:inside] relative inline-flex">
                                    <button type="button" class="hs-dropdown-toggle cursor-pointer btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-2 hover:bg-primary hover:border-primary hover:text-white focus:bg-primary focus:border-primary focus:text-white  focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        2026 <i class="icon-chevron-down"></i>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-50 bg-white border border-border-color shadow rounded-md mt-2 z-1" role="menu" aria-orientation="vertical" tabindex="-1">
                                        <div class="p-2 space-y-1"> 
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2026
                                            </a>
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2025
                                            </a> 
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2024
                                            </a> 
                                        </div>
                                    </div>
                                </div>
							</div>
							<div id="inv-category-chart" class="w-full" style="min-height: 255px;"><div id="apexchartswwclg4s1h" class="apexcharts-canvas apexchartswwclg4s1h apexcharts-theme-light" style="width: 437px; height: 240px;"><svg xmlns="http://www.w3.org/2000/svg" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="437" height="240" role="application" aria-label="bar chart with 1 data series: series-1" tabindex="0"><foreignObject x="0" y="0" width="437" height="240"><style type="text/css">.apexcharts-flip-y {
  transform: scaleY(-1) translateY(-100%);
  transform-origin: top;
  transform-box: fill-box;
}
.apexcharts-flip-x {
  transform: scaleX(-1);
  transform-origin: center;
  transform-box: fill-box;
}
.apexcharts-legend {
  display: flex;
  overflow: auto;
  padding: 0 10px;
}
.apexcharts-legend.apexcharts-legend-group-horizontal {
  flex-direction: column;
}
.apexcharts-legend-group {
  display: flex;
}
.apexcharts-legend-group-vertical {
  flex-direction: column-reverse;
}
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
  flex-wrap: wrap
}
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  flex-direction: column;
  bottom: 0;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  justify-content: flex-start;
  align-items: flex-start;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
  justify-content: center;
  align-items: center;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
  justify-content: flex-end;
  align-items: flex-end;
}
.apexcharts-legend-series {
  cursor: pointer;
  line-height: normal;
  display: flex;
  align-items: center;
}
.apexcharts-legend-text {
  position: relative;
  font-size: 14px;
}
.apexcharts-legend-text *, .apexcharts-legend-marker * {
  pointer-events: none;
}
.apexcharts-legend-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-right: 1px;
}

.apexcharts-legend-series.apexcharts-no-click {
  cursor: auto;
}
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
  display: none !important;
}
.apexcharts-inactive-legend {
  opacity: 0.45;
} </style></foreignObject><g class="apexcharts-inner apexcharts-graphical" transform="translate(68.89687728881836, 30)"><defs><linearGradient id="SvgjsGradient15" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(216,227,240,0.4)" stop-opacity="0.4"></stop><stop offset="1" stop-color="rgba(190,209,230,0.5)" stop-opacity="0.5"></stop><stop offset="1" stop-color="rgba(190,209,230,0.5)" stop-opacity="0.5"></stop></linearGradient><clipPath id="gridRectMaskwwclg4s1h"><rect x="-2" y="-2" width="364.7415990829468" height="180.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskwwclg4s1h"><rect x="-2" y="-2" width="364.7415990829468" height="180.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskwwclg4s1h"><rect x="-2" y="0" width="364.7415990829468" height="176.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskwwclg4s1h"></clipPath><clipPath id="nonForecastMaskwwclg4s1h"></clipPath></defs><rect x="0" y="0" width="0" height="176.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="#b6b6b6" stroke-dasharray="3" fill="url(#SvgjsGradient15)" class="apexcharts-xcrosshairs" y2="176.878400308609" filter="none" fill-opacity="0.9"></rect><line x1="0" y1="176.878400308609" x2="0" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="60.1235998471578" y1="176.878400308609" x2="60.1235998471578" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="120.2471996943156" y1="176.878400308609" x2="120.2471996943156" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="180.3707995414734" y1="176.878400308609" x2="180.3707995414734" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="240.4943993886312" y1="176.878400308609" x2="240.4943993886312" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="300.617999235789" y1="176.878400308609" x2="300.617999235789" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line x1="360.7415990829468" y1="176.878400308609" x2="360.7415990829468" y2="182.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal"><line x1="0" y1="29.479733384768167" x2="360.7415990829468" y2="29.479733384768167" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="58.959466769536334" x2="360.7415990829468" y2="58.959466769536334" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="88.4392001543045" x2="360.7415990829468" y2="88.4392001543045" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="117.91893353907267" x2="360.7415990829468" y2="117.91893353907267" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="147.39866692384084" x2="360.7415990829468" y2="147.39866692384084" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical"></g><line x1="0" y1="176.878400308609" x2="360.7415990829468" y2="176.878400308609" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="176.878400308609" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders"><line x1="0" y1="0" x2="360.7415990829468" y2="0" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="176.878400308609" x2="360.7415990829468" y2="176.878400308609" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="176.878400308609" x2="360.7415990829468" y2="176.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="176.878400308609" stroke="var(--color-border-color)" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-bar-series apexcharts-plot-series"><g class="apexcharts-series" rel="1" seriesName="series-1" data:realIndex="0"><path d="M 3.101 6.632940011572838 L 327.78079915936786 6.632940011572838 C 329.28079915936786 6.632940011572838 330.78079915936786 8.132940011572838 330.78079915936786 9.632940011572838 L 330.78079915936786 19.84679337319533 C 330.78079915936786 21.34679337319533 329.28079915936786 22.84679337319533 327.78079915936786 22.84679337319533 L 3.101 22.84679337319533 C 1.601 22.84679337319533 0.101 21.34679337319533 0.101 19.84679337319533 L 0.101 9.632940011572838 C 0.101 8.132940011572838 1.601 6.632940011572838 3.101 6.632940011572838 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 6.632940011572838 L 327.78079915936786 6.632940011572838 C 329.28079915936786 6.632940011572838 330.78079915936786 8.132940011572838 330.78079915936786 9.632940011572838 L 330.78079915936786 19.84679337319533 C 330.78079915936786 21.34679337319533 329.28079915936786 22.84679337319533 327.78079915936786 22.84679337319533 L 3.101 22.84679337319533 C 1.601 22.84679337319533 0.101 21.34679337319533 0.101 19.84679337319533 L 0.101 9.632940011572838 C 0.101 8.132940011572838 1.601 6.632940011572838 3.101 6.632940011572838 Z " pathFrom="M 0.101 6.632940011572838 L 0.101 6.632940011572838 L 0.101 22.84679337319533 L 0.101 22.84679337319533 L 0.101 22.84679337319533 L 0.101 22.84679337319533 L 0.101 22.84679337319533 L 0.101 6.632940011572838 Z" cy="36.11267339634101" cx="330.7797991593679" j="0" val="110" barHeight="16.21385336162249" barWidth="330.67979915936786"></path><path d="M 3.101 36.11267339634101 L 282.6880992739995 36.11267339634101 C 284.1880992739995 36.11267339634101 285.6880992739995 37.61267339634101 285.6880992739995 39.11267339634101 L 285.6880992739995 49.3265267579635 C 285.6880992739995 50.8265267579635 284.1880992739995 52.3265267579635 282.6880992739995 52.3265267579635 L 3.101 52.3265267579635 C 1.601 52.3265267579635 0.101 50.8265267579635 0.101 49.3265267579635 L 0.101 39.11267339634101 C 0.101 37.61267339634101 1.601 36.11267339634101 3.101 36.11267339634101 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 36.11267339634101 L 282.6880992739995 36.11267339634101 C 284.1880992739995 36.11267339634101 285.6880992739995 37.61267339634101 285.6880992739995 39.11267339634101 L 285.6880992739995 49.3265267579635 C 285.6880992739995 50.8265267579635 284.1880992739995 52.3265267579635 282.6880992739995 52.3265267579635 L 3.101 52.3265267579635 C 1.601 52.3265267579635 0.101 50.8265267579635 0.101 49.3265267579635 L 0.101 39.11267339634101 C 0.101 37.61267339634101 1.601 36.11267339634101 3.101 36.11267339634101 Z " pathFrom="M 0.101 36.11267339634101 L 0.101 36.11267339634101 L 0.101 52.3265267579635 L 0.101 52.3265267579635 L 0.101 52.3265267579635 L 0.101 52.3265267579635 L 0.101 52.3265267579635 L 0.101 36.11267339634101 Z" cy="65.59240678110918" cx="285.68709927399954" j="1" val="95" barHeight="16.21385336162249" barWidth="285.5870992739995"></path><path d="M 3.101 65.59240678110918 L 231.5830394039154 65.59240678110918 C 233.0830394039154 65.59240678110918 234.5830394039154 67.09240678110918 234.5830394039154 68.59240678110918 L 234.5830394039154 78.80626014273167 C 234.5830394039154 80.30626014273167 233.0830394039154 81.80626014273167 231.5830394039154 81.80626014273167 L 3.101 81.80626014273167 C 1.601 81.80626014273167 0.101 80.30626014273167 0.101 78.80626014273167 L 0.101 68.59240678110918 C 0.101 67.09240678110918 1.601 65.59240678110918 3.101 65.59240678110918 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 65.59240678110918 L 231.5830394039154 65.59240678110918 C 233.0830394039154 65.59240678110918 234.5830394039154 67.09240678110918 234.5830394039154 68.59240678110918 L 234.5830394039154 78.80626014273167 C 234.5830394039154 80.30626014273167 233.0830394039154 81.80626014273167 231.5830394039154 81.80626014273167 L 3.101 81.80626014273167 C 1.601 81.80626014273167 0.101 80.30626014273167 0.101 78.80626014273167 L 0.101 68.59240678110918 C 0.101 67.09240678110918 1.601 65.59240678110918 3.101 65.59240678110918 Z " pathFrom="M 0.101 65.59240678110918 L 0.101 65.59240678110918 L 0.101 81.80626014273167 L 0.101 81.80626014273167 L 0.101 81.80626014273167 L 0.101 81.80626014273167 L 0.101 81.80626014273167 L 0.101 65.59240678110918 Z" cy="95.07214016587734" cx="234.5820394039154" j="2" val="78" barHeight="16.21385336162249" barWidth="234.4820394039154"></path><path d="M 3.101 95.07214016587734 L 183.48415952618916 95.07214016587734 C 184.98415952618916 95.07214016587734 186.48415952618916 96.57214016587734 186.48415952618916 98.07214016587734 L 186.48415952618916 108.28599352749984 C 186.48415952618916 109.78599352749984 184.98415952618916 111.28599352749984 183.48415952618916 111.28599352749984 L 3.101 111.28599352749984 C 1.601 111.28599352749984 0.101 109.78599352749984 0.101 108.28599352749984 L 0.101 98.07214016587734 C 0.101 96.57214016587734 1.601 95.07214016587734 3.101 95.07214016587734 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 95.07214016587734 L 183.48415952618916 95.07214016587734 C 184.98415952618916 95.07214016587734 186.48415952618916 96.57214016587734 186.48415952618916 98.07214016587734 L 186.48415952618916 108.28599352749984 C 186.48415952618916 109.78599352749984 184.98415952618916 111.28599352749984 183.48415952618916 111.28599352749984 L 3.101 111.28599352749984 C 1.601 111.28599352749984 0.101 109.78599352749984 0.101 108.28599352749984 L 0.101 98.07214016587734 C 0.101 96.57214016587734 1.601 95.07214016587734 3.101 95.07214016587734 Z " pathFrom="M 0.101 95.07214016587734 L 0.101 95.07214016587734 L 0.101 111.28599352749984 L 0.101 111.28599352749984 L 0.101 111.28599352749984 L 0.101 111.28599352749984 L 0.101 111.28599352749984 L 0.101 95.07214016587734 Z" cy="124.55187355064551" cx="186.48315952618916" j="3" val="62" barHeight="16.21385336162249" barWidth="186.38315952618916"></path><path d="M 3.101 124.55187355064551 L 162.44089957968393 124.55187355064551 C 163.94089957968393 124.55187355064551 165.44089957968393 126.05187355064551 165.44089957968393 127.55187355064551 L 165.44089957968393 137.765726912268 C 165.44089957968393 139.265726912268 163.94089957968393 140.765726912268 162.44089957968393 140.765726912268 L 3.101 140.765726912268 C 1.601 140.765726912268 0.101 139.265726912268 0.101 137.765726912268 L 0.101 127.55187355064551 C 0.101 126.05187355064551 1.601 124.55187355064551 3.101 124.55187355064551 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 124.55187355064551 L 162.44089957968393 124.55187355064551 C 163.94089957968393 124.55187355064551 165.44089957968393 126.05187355064551 165.44089957968393 127.55187355064551 L 165.44089957968393 137.765726912268 C 165.44089957968393 139.265726912268 163.94089957968393 140.765726912268 162.44089957968393 140.765726912268 L 3.101 140.765726912268 C 1.601 140.765726912268 0.101 139.265726912268 0.101 137.765726912268 L 0.101 127.55187355064551 C 0.101 126.05187355064551 1.601 124.55187355064551 3.101 124.55187355064551 Z " pathFrom="M 0.101 124.55187355064551 L 0.101 124.55187355064551 L 0.101 140.765726912268 L 0.101 140.765726912268 L 0.101 140.765726912268 L 0.101 140.765726912268 L 0.101 140.765726912268 L 0.101 124.55187355064551 Z" cy="154.03160693541366" cx="165.43989957968392" j="4" val="55" barHeight="16.21385336162249" barWidth="165.33989957968393"></path><path d="M 3.101 154.03160693541366 L 111.33583970959981 154.03160693541366 C 112.83583970959981 154.03160693541366 114.33583970959981 155.53160693541366 114.33583970959981 157.03160693541366 L 114.33583970959981 167.24546029703615 C 114.33583970959981 168.74546029703615 112.83583970959981 170.24546029703615 111.33583970959981 170.24546029703615 L 3.101 170.24546029703615 C 1.601 170.24546029703615 0.101 168.74546029703615 0.101 167.24546029703615 L 0.101 157.03160693541366 C 0.101 155.53160693541366 1.601 154.03160693541366 3.101 154.03160693541366 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="square" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskwwclg4s1h)" pathTo="M 3.101 154.03160693541366 L 111.33583970959981 154.03160693541366 C 112.83583970959981 154.03160693541366 114.33583970959981 155.53160693541366 114.33583970959981 157.03160693541366 L 114.33583970959981 167.24546029703615 C 114.33583970959981 168.74546029703615 112.83583970959981 170.24546029703615 111.33583970959981 170.24546029703615 L 3.101 170.24546029703615 C 1.601 170.24546029703615 0.101 168.74546029703615 0.101 167.24546029703615 L 0.101 157.03160693541366 C 0.101 155.53160693541366 1.601 154.03160693541366 3.101 154.03160693541366 Z " pathFrom="M 0.101 154.03160693541366 L 0.101 154.03160693541366 L 0.101 170.24546029703615 L 0.101 170.24546029703615 L 0.101 170.24546029703615 L 0.101 170.24546029703615 L 0.101 170.24546029703615 L 0.101 154.03160693541366 Z" cy="183.51134032018183" cx="114.3348397095998" j="5" val="38" barHeight="16.21385336162249" barWidth="114.23483970959981"></path><g class="apexcharts-bar-goals-markers"><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwwclg4s1h)"></g></g><g class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g></g><g class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realIndex="0"></g></g><line x1="0" y1="0" x2="360.7415990829468" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="360.7415990829468" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-yaxis apexcharts-xaxis-inversed" rel="0"><g class="apexcharts-yaxis-texts-g apexcharts-xaxis-inversed-texts-g" transform="translate(0, 0)"><text x="-15" y="16.079854573509913" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Electronics</tspan><title>Electronics</title></text><text x="-15" y="45.55958795827808" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Clothing</tspan><title>Clothing</title></text><text x="-15" y="75.03932134304625" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Machines</tspan><title>Machines</title></text><text x="-15" y="104.51905472781442" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Sports</tspan><title>Sports</title></text><text x="-15" y="133.9987881125826" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Bikes</tspan><title>Bikes</title></text><text x="-15" y="163.47852149735075" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Books</tspan><title>Books</title></text></g></g><g class="apexcharts-xaxis apexcharts-yaxis-inversed"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -6.666666666666667)"><text x="360.7415990829468" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>120</tspan><title>120</title></text><text x="300.517999235789" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>100</tspan><title>100</title></text><text x="240.29439938863118" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>80</tspan><title>80</title></text><text x="180.07079954147338" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>60</tspan><title>60</title></text><text x="119.84719969431558" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>40</tspan><title>40</title></text><text x="59.62359984715778" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>20</tspan><title>20</title></text><text x="-0.6000000000000227" y="206.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>0</tspan><title>0</title></text></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-legend" style="max-height: 120px;"></div><div class="apexcharts-sr-status" role="status" aria-live="polite" aria-atomic="true"></div><div class="apexcharts-tooltip apexcharts-theme-dark" role="tooltip" aria-live="polite" aria-atomic="true" data-placement="top" style="left: 68.8969px; top: 99.4316px; --apx-tt-arrow-x: 57.21844482421875px;" aria-hidden="true"><div class="apexcharts-tooltip-arrow"></div><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">Books</div><div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0 apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgba(5, 150, 105, 0.85);"><svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="6" cy="6" r="5" fill="currentColor"></circle></svg></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">series-1: </span><span class="apexcharts-tooltip-text-y-value">38</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
							<p class="text-[11px] text-default mb-0 inline-flex items-center">
								<span class="size-1.5 rounded-full bg-pink me-2"></span>No of Products increased by <span class="text-success font-medium ms-1">+20% from last Week</span>
							</p>
						</div>

						<!-- Product Stock Levels -->
						<div class="bg-white border border-border-color rounded-md p-4 xxl:col-span-5 lg:col-span-12 md:col-span-12 min-w-0">
							<div class="flex items-center justify-between mb-2">
								<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Product Stock Levels</h3>
								<div class="hs-dropdown [--placement:bottom-right] [--auto-close:inside] relative inline-flex">
                                    <button type="button" class="hs-dropdown-toggle cursor-pointer btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-2 hover:bg-primary hover:border-primary hover:text-white focus:bg-primary focus:border-primary focus:text-white  focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        2026 <i class="icon-chevron-down"></i>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-50 bg-white border border-border-color shadow rounded-md mt-2 z-1" role="menu" aria-orientation="vertical" tabindex="-1">
                                        <div class="p-2 space-y-1"> 
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2026
                                            </a>
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2025
                                            </a> 
                                            <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                                2024
                                            </a> 
                                        </div>
                                    </div>
                                </div>
							</div>
							<div id="inv-stock-levels-chart" class="w-full" style="min-height: 255px;"><div id="apexchartsg0roks97" class="apexcharts-canvas apexchartsg0roks97 apexcharts-theme-light" style="width: 919px; height: 240px;"><svg xmlns="http://www.w3.org/2000/svg" class="apexcharts-svg apexcharts-zoomable hovering-zoom" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="919" height="240" role="application" aria-label="line chart with 2 data series: Total Products, Out Of Stock" tabindex="0"><foreignObject x="0" y="0" width="919" height="240"><style type="text/css">.apexcharts-flip-y {
  transform: scaleY(-1) translateY(-100%);
  transform-origin: top;
  transform-box: fill-box;
}
.apexcharts-flip-x {
  transform: scaleX(-1);
  transform-origin: center;
  transform-box: fill-box;
}
.apexcharts-legend {
  display: flex;
  overflow: auto;
  padding: 0 10px;
}
.apexcharts-legend.apexcharts-legend-group-horizontal {
  flex-direction: column;
}
.apexcharts-legend-group {
  display: flex;
}
.apexcharts-legend-group-vertical {
  flex-direction: column-reverse;
}
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
  flex-wrap: wrap
}
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  flex-direction: column;
  bottom: 0;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  justify-content: flex-start;
  align-items: flex-start;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
  justify-content: center;
  align-items: center;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
  justify-content: flex-end;
  align-items: flex-end;
}
.apexcharts-legend-series {
  cursor: pointer;
  line-height: normal;
  display: flex;
  align-items: center;
}
.apexcharts-legend-text {
  position: relative;
  font-size: 14px;
}
.apexcharts-legend-text *, .apexcharts-legend-marker * {
  pointer-events: none;
}
.apexcharts-legend-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-right: 1px;
}

.apexcharts-legend-series.apexcharts-no-click {
  cursor: auto;
}
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
  display: none !important;
}
.apexcharts-inactive-legend {
  opacity: 0.45;
} </style></foreignObject><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g class="apexcharts-yaxis" rel="0" transform="translate(0.6353511810302734, 0)"><g class="apexcharts-yaxis-texts-g"><text x="20" y="33.666666666666664" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>800</tspan><title>800</title></text><text x="20" y="77.88626674381891" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>600</tspan><title>600</title></text><text x="20" y="122.10586682097116" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>400</tspan><title>400</title></text><text x="20" y="166.3254668981234" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>200</tspan><title>200</title></text><text x="20" y="210.54506697527566" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>0</tspan><title>0</title></text></g></g><g class="apexcharts-inner apexcharts-graphical" transform="translate(65.82346198342063, 30)"><defs><clipPath id="gridRectMaskg0roks97"><rect x="-3" y="-3" width="826.7968254609541" height="182.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskg0roks97"><rect x="-35.18811080239036" y="-3" width="891.1730470657349" height="182.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskg0roks97"><rect x="-35.18811080239036" y="0" width="891.1730470657349" height="176.878400308609" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskg0roks97"></clipPath><clipPath id="nonForecastMaskg0roks97"></clipPath></defs><line x1="-0.49999949715354575" y1="0" x2="-0.49999949715354575" y2="176.878400308609" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="-0.49999949715354575" y="0" width="1" height="176.878400308609" fill="#B1B9C4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal"><line x1="-32.18811080239036" y1="44.21960007715225" x2="852.9849362633445" y2="44.21960007715225" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="-32.18811080239036" y1="88.4392001543045" x2="852.9849362633445" y2="88.4392001543045" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="-32.18811080239036" y1="132.65880023145675" x2="852.9849362633445" y2="132.65880023145675" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical"></g><line x1="0" y1="176.878400308609" x2="820.7968254609541" y2="176.878400308609" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="176.878400308609" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders"><line x1="-32.18811080239036" y1="0" x2="852.9849362633445" y2="0" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="-32.18811080239036" y1="176.878400308609" x2="852.9849362633445" y2="176.878400308609" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-bar-series apexcharts-plot-series"><g class="apexcharts-series" rel="1" seriesName="TotalxProducts" data:realIndex="0"><path d="M -14.923578644744621 173.879400308609 L -14.923578644744621 131.23784022374153 C -14.923578644744621 129.73784022374153 -13.423578644744621 128.23784022374153 -11.923578644744621 128.23784022374153 L 11.923578644744621 128.23784022374153 C 13.423578644744621 128.23784022374153 14.923578644744621 129.73784022374153 14.923578644744621 131.23784022374153 L 14.923578644744621 173.879400308609 C 14.923578644744621 175.379400308609 13.423578644744621 176.879400308609 11.923578644744621 176.879400308609 L -11.923578644744621 176.879400308609 C -13.423578644744621 176.879400308609 -14.923578644744621 175.379400308609 -14.923578644744621 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M -14.923578644744621 173.879400308609 L -14.923578644744621 131.23784022374153 C -14.923578644744621 129.73784022374153 -13.423578644744621 128.23784022374153 -11.923578644744621 128.23784022374153 L 11.923578644744621 128.23784022374153 C 13.423578644744621 128.23784022374153 14.923578644744621 129.73784022374153 14.923578644744621 131.23784022374153 L 14.923578644744621 173.879400308609 C 14.923578644744621 175.379400308609 13.423578644744621 176.879400308609 11.923578644744621 176.879400308609 L -11.923578644744621 176.879400308609 C -13.423578644744621 176.879400308609 -14.923578644744621 175.379400308609 -14.923578644744621 173.879400308609 Z " pathFrom="M -14.923578644744621 176.879400308609 L -14.923578644744621 176.879400308609 L 14.923578644744621 176.879400308609 L 14.923578644744621 176.879400308609 L 14.923578644744621 176.879400308609 L 14.923578644744621 176.879400308609 L 14.923578644744621 176.879400308609 L -14.923578644744621 176.879400308609 Z" cy="128.23684022374152" cx="14.923578644744621" j="0" val="220" barHeight="48.64156008486747" barWidth="29.847157289489243"></path><path d="M 59.69431457897848 173.879400308609 L 59.69431457897848 126.8158802160263 C 59.69431457897848 125.3158802160263 61.19431457897848 123.8158802160263 62.69431457897848 123.8158802160263 L 86.54147186846772 123.8158802160263 C 88.04147186846772 123.8158802160263 89.54147186846772 125.3158802160263 89.54147186846772 126.8158802160263 L 89.54147186846772 173.879400308609 C 89.54147186846772 175.379400308609 88.04147186846772 176.879400308609 86.54147186846772 176.879400308609 L 62.69431457897848 176.879400308609 C 61.19431457897848 176.879400308609 59.69431457897848 175.379400308609 59.69431457897848 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 59.69431457897848 173.879400308609 L 59.69431457897848 126.8158802160263 C 59.69431457897848 125.3158802160263 61.19431457897848 123.8158802160263 62.69431457897848 123.8158802160263 L 86.54147186846772 123.8158802160263 C 88.04147186846772 123.8158802160263 89.54147186846772 125.3158802160263 89.54147186846772 126.8158802160263 L 89.54147186846772 173.879400308609 C 89.54147186846772 175.379400308609 88.04147186846772 176.879400308609 86.54147186846772 176.879400308609 L 62.69431457897848 176.879400308609 C 61.19431457897848 176.879400308609 59.69431457897848 175.379400308609 59.69431457897848 173.879400308609 Z " pathFrom="M 59.69431457897848 176.879400308609 L 59.69431457897848 176.879400308609 L 89.54147186846772 176.879400308609 L 89.54147186846772 176.879400308609 L 89.54147186846772 176.879400308609 L 89.54147186846772 176.879400308609 L 89.54147186846772 176.879400308609 L 59.69431457897848 176.879400308609 Z" cy="123.8148802160263" cx="89.54147186846772" j="1" val="240" barHeight="53.0635200925827" barWidth="29.847157289489243"></path><path d="M 134.31220780270158 173.879400308609 L 134.31220780270158 135.65980023145676 C 134.31220780270158 134.15980023145676 135.81220780270158 132.65980023145676 137.31220780270158 132.65980023145676 L 161.15936509219082 132.65980023145676 C 162.65936509219082 132.65980023145676 164.15936509219082 134.15980023145676 164.15936509219082 135.65980023145676 L 164.15936509219082 173.879400308609 C 164.15936509219082 175.379400308609 162.65936509219082 176.879400308609 161.15936509219082 176.879400308609 L 137.31220780270158 176.879400308609 C 135.81220780270158 176.879400308609 134.31220780270158 175.379400308609 134.31220780270158 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 134.31220780270158 173.879400308609 L 134.31220780270158 135.65980023145676 C 134.31220780270158 134.15980023145676 135.81220780270158 132.65980023145676 137.31220780270158 132.65980023145676 L 161.15936509219082 132.65980023145676 C 162.65936509219082 132.65980023145676 164.15936509219082 134.15980023145676 164.15936509219082 135.65980023145676 L 164.15936509219082 173.879400308609 C 164.15936509219082 175.379400308609 162.65936509219082 176.879400308609 161.15936509219082 176.879400308609 L 137.31220780270158 176.879400308609 C 135.81220780270158 176.879400308609 134.31220780270158 175.379400308609 134.31220780270158 173.879400308609 Z " pathFrom="M 134.31220780270158 176.879400308609 L 134.31220780270158 176.879400308609 L 164.15936509219082 176.879400308609 L 164.15936509219082 176.879400308609 L 164.15936509219082 176.879400308609 L 164.15936509219082 176.879400308609 L 164.15936509219082 176.879400308609 L 134.31220780270158 176.879400308609 Z" cy="132.65880023145675" cx="164.15936509219082" j="2" val="200" barHeight="44.21960007715224" barWidth="29.847157289489243"></path><path d="M 208.93010102642467 173.879400308609 L 208.93010102642467 122.39392020831109 C 208.93010102642467 120.89392020831109 210.43010102642467 119.39392020831109 211.93010102642467 119.39392020831109 L 235.7772583159139 119.39392020831109 C 237.2772583159139 119.39392020831109 238.7772583159139 120.89392020831109 238.7772583159139 122.39392020831109 L 238.7772583159139 173.879400308609 C 238.7772583159139 175.379400308609 237.2772583159139 176.879400308609 235.7772583159139 176.879400308609 L 211.93010102642467 176.879400308609 C 210.43010102642467 176.879400308609 208.93010102642467 175.379400308609 208.93010102642467 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 208.93010102642467 173.879400308609 L 208.93010102642467 122.39392020831109 C 208.93010102642467 120.89392020831109 210.43010102642467 119.39392020831109 211.93010102642467 119.39392020831109 L 235.7772583159139 119.39392020831109 C 237.2772583159139 119.39392020831109 238.7772583159139 120.89392020831109 238.7772583159139 122.39392020831109 L 238.7772583159139 173.879400308609 C 238.7772583159139 175.379400308609 237.2772583159139 176.879400308609 235.7772583159139 176.879400308609 L 211.93010102642467 176.879400308609 C 210.43010102642467 176.879400308609 208.93010102642467 175.379400308609 208.93010102642467 173.879400308609 Z " pathFrom="M 208.93010102642467 176.879400308609 L 208.93010102642467 176.879400308609 L 238.7772583159139 176.879400308609 L 238.7772583159139 176.879400308609 L 238.7772583159139 176.879400308609 L 238.7772583159139 176.879400308609 L 238.7772583159139 176.879400308609 L 208.93010102642467 176.879400308609 Z" cy="119.39292020831108" cx="238.7772583159139" j="3" val="260" barHeight="57.48548010029792" barWidth="29.847157289489243"></path><path d="M 283.5479942501478 173.879400308609 L 283.5479942501478 18.920056027774812 C 283.5479942501478 17.420056027774812 285.0479942501478 15.920056027774814 286.5479942501478 15.920056027774814 L 310.395151539637 15.920056027774814 C 311.895151539637 15.920056027774814 313.395151539637 17.420056027774812 313.395151539637 18.920056027774812 L 313.395151539637 173.879400308609 C 313.395151539637 175.379400308609 311.895151539637 176.879400308609 310.395151539637 176.879400308609 L 286.5479942501478 176.879400308609 C 285.0479942501478 176.879400308609 283.5479942501478 175.379400308609 283.5479942501478 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 283.5479942501478 173.879400308609 L 283.5479942501478 18.920056027774812 C 283.5479942501478 17.420056027774812 285.0479942501478 15.920056027774814 286.5479942501478 15.920056027774814 L 310.395151539637 15.920056027774814 C 311.895151539637 15.920056027774814 313.395151539637 17.420056027774812 313.395151539637 18.920056027774812 L 313.395151539637 173.879400308609 C 313.395151539637 175.379400308609 311.895151539637 176.879400308609 310.395151539637 176.879400308609 L 286.5479942501478 176.879400308609 C 285.0479942501478 176.879400308609 283.5479942501478 175.379400308609 283.5479942501478 173.879400308609 Z " pathFrom="M 283.5479942501478 176.879400308609 L 283.5479942501478 176.879400308609 L 313.395151539637 176.879400308609 L 313.395151539637 176.879400308609 L 313.395151539637 176.879400308609 L 313.395151539637 176.879400308609 L 313.395151539637 176.879400308609 L 283.5479942501478 176.879400308609 Z" cy="15.919056027774815" cx="313.395151539637" j="4" val="728" barHeight="160.9593442808342" barWidth="29.847157289489243"></path><path d="M 358.16588747387084 173.879400308609 L 358.16588747387084 109.12804018516542 C 358.16588747387084 107.62804018516542 359.66588747387084 106.12804018516542 361.16588747387084 106.12804018516542 L 385.0130447633601 106.12804018516542 C 386.5130447633601 106.12804018516542 388.0130447633601 107.62804018516542 388.0130447633601 109.12804018516542 L 388.0130447633601 173.879400308609 C 388.0130447633601 175.379400308609 386.5130447633601 176.879400308609 385.0130447633601 176.879400308609 L 361.16588747387084 176.879400308609 C 359.66588747387084 176.879400308609 358.16588747387084 175.379400308609 358.16588747387084 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 358.16588747387084 173.879400308609 L 358.16588747387084 109.12804018516542 C 358.16588747387084 107.62804018516542 359.66588747387084 106.12804018516542 361.16588747387084 106.12804018516542 L 385.0130447633601 106.12804018516542 C 386.5130447633601 106.12804018516542 388.0130447633601 107.62804018516542 388.0130447633601 109.12804018516542 L 388.0130447633601 173.879400308609 C 388.0130447633601 175.379400308609 386.5130447633601 176.879400308609 385.0130447633601 176.879400308609 L 361.16588747387084 176.879400308609 C 359.66588747387084 176.879400308609 358.16588747387084 175.379400308609 358.16588747387084 173.879400308609 Z " pathFrom="M 358.16588747387084 176.879400308609 L 358.16588747387084 176.879400308609 L 388.0130447633601 176.879400308609 L 388.0130447633601 176.879400308609 L 388.0130447633601 176.879400308609 L 388.0130447633601 176.879400308609 L 388.0130447633601 176.879400308609 L 358.16588747387084 176.879400308609 Z" cy="106.12704018516541" cx="388.0130447633601" j="5" val="320" barHeight="70.75136012344359" barWidth="29.847157289489243"></path><path d="M 432.78378069759395 173.879400308609 L 432.78378069759395 117.97196020059586 C 432.78378069759395 116.47196020059586 434.28378069759395 114.97196020059586 435.78378069759395 114.97196020059586 L 459.6309379870832 114.97196020059586 C 461.1309379870832 114.97196020059586 462.6309379870832 116.47196020059586 462.6309379870832 117.97196020059586 L 462.6309379870832 173.879400308609 C 462.6309379870832 175.379400308609 461.1309379870832 176.879400308609 459.6309379870832 176.879400308609 L 435.78378069759395 176.879400308609 C 434.28378069759395 176.879400308609 432.78378069759395 175.379400308609 432.78378069759395 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 432.78378069759395 173.879400308609 L 432.78378069759395 117.97196020059586 C 432.78378069759395 116.47196020059586 434.28378069759395 114.97196020059586 435.78378069759395 114.97196020059586 L 459.6309379870832 114.97196020059586 C 461.1309379870832 114.97196020059586 462.6309379870832 116.47196020059586 462.6309379870832 117.97196020059586 L 462.6309379870832 173.879400308609 C 462.6309379870832 175.379400308609 461.1309379870832 176.879400308609 459.6309379870832 176.879400308609 L 435.78378069759395 176.879400308609 C 434.28378069759395 176.879400308609 432.78378069759395 175.379400308609 432.78378069759395 173.879400308609 Z " pathFrom="M 432.78378069759395 176.879400308609 L 432.78378069759395 176.879400308609 L 462.6309379870832 176.879400308609 L 462.6309379870832 176.879400308609 L 462.6309379870832 176.879400308609 L 462.6309379870832 176.879400308609 L 462.6309379870832 176.879400308609 L 432.78378069759395 176.879400308609 Z" cy="114.97096020059585" cx="462.6309379870832" j="6" val="280" barHeight="61.90744010801315" barWidth="29.847157289489243"></path><path d="M 507.401673921317 173.879400308609 L 507.401673921317 100.28412016973496 C 507.401673921317 98.78412016973496 508.901673921317 97.28412016973496 510.401673921317 97.28412016973496 L 534.2488312108062 97.28412016973496 C 535.7488312108062 97.28412016973496 537.2488312108062 98.78412016973496 537.2488312108062 100.28412016973496 L 537.2488312108062 173.879400308609 C 537.2488312108062 175.379400308609 535.7488312108062 176.879400308609 534.2488312108062 176.879400308609 L 510.401673921317 176.879400308609 C 508.901673921317 176.879400308609 507.401673921317 175.379400308609 507.401673921317 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 507.401673921317 173.879400308609 L 507.401673921317 100.28412016973496 C 507.401673921317 98.78412016973496 508.901673921317 97.28412016973496 510.401673921317 97.28412016973496 L 534.2488312108062 97.28412016973496 C 535.7488312108062 97.28412016973496 537.2488312108062 98.78412016973496 537.2488312108062 100.28412016973496 L 537.2488312108062 173.879400308609 C 537.2488312108062 175.379400308609 535.7488312108062 176.879400308609 534.2488312108062 176.879400308609 L 510.401673921317 176.879400308609 C 508.901673921317 176.879400308609 507.401673921317 175.379400308609 507.401673921317 173.879400308609 Z " pathFrom="M 507.401673921317 176.879400308609 L 507.401673921317 176.879400308609 L 537.2488312108062 176.879400308609 L 537.2488312108062 176.879400308609 L 537.2488312108062 176.879400308609 L 537.2488312108062 176.879400308609 L 537.2488312108062 176.879400308609 L 507.401673921317 176.879400308609 Z" cy="97.28312016973496" cx="537.2488312108062" j="7" val="360" barHeight="79.59528013887405" barWidth="29.847157289489243"></path><path d="M 582.0195671450402 173.879400308609 L 582.0195671450402 89.2292201504469 C 582.0195671450402 87.7292201504469 583.5195671450402 86.2292201504469 585.0195671450402 86.2292201504469 L 608.8667244345295 86.2292201504469 C 610.3667244345295 86.2292201504469 611.8667244345295 87.7292201504469 611.8667244345295 89.2292201504469 L 611.8667244345295 173.879400308609 C 611.8667244345295 175.379400308609 610.3667244345295 176.879400308609 608.8667244345295 176.879400308609 L 585.0195671450402 176.879400308609 C 583.5195671450402 176.879400308609 582.0195671450402 175.379400308609 582.0195671450402 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 582.0195671450402 173.879400308609 L 582.0195671450402 89.2292201504469 C 582.0195671450402 87.7292201504469 583.5195671450402 86.2292201504469 585.0195671450402 86.2292201504469 L 608.8667244345295 86.2292201504469 C 610.3667244345295 86.2292201504469 611.8667244345295 87.7292201504469 611.8667244345295 89.2292201504469 L 611.8667244345295 173.879400308609 C 611.8667244345295 175.379400308609 610.3667244345295 176.879400308609 608.8667244345295 176.879400308609 L 585.0195671450402 176.879400308609 C 583.5195671450402 176.879400308609 582.0195671450402 175.379400308609 582.0195671450402 173.879400308609 Z " pathFrom="M 582.0195671450402 176.879400308609 L 582.0195671450402 176.879400308609 L 611.8667244345295 176.879400308609 L 611.8667244345295 176.879400308609 L 611.8667244345295 176.879400308609 L 611.8667244345295 176.879400308609 L 611.8667244345295 176.879400308609 L 582.0195671450402 176.879400308609 Z" cy="86.2282201504469" cx="611.8667244345295" j="8" val="410" barHeight="90.6501801581621" barWidth="29.847157289489243"></path><path d="M 656.6374603687633 173.879400308609 L 656.6374603687633 104.70608017745019 C 656.6374603687633 103.20608017745019 658.1374603687633 101.70608017745019 659.6374603687633 101.70608017745019 L 683.4846176582525 101.70608017745019 C 684.9846176582525 101.70608017745019 686.4846176582525 103.20608017745019 686.4846176582525 104.70608017745019 L 686.4846176582525 173.879400308609 C 686.4846176582525 175.379400308609 684.9846176582525 176.879400308609 683.4846176582525 176.879400308609 L 659.6374603687633 176.879400308609 C 658.1374603687633 176.879400308609 656.6374603687633 175.379400308609 656.6374603687633 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 656.6374603687633 173.879400308609 L 656.6374603687633 104.70608017745019 C 656.6374603687633 103.20608017745019 658.1374603687633 101.70608017745019 659.6374603687633 101.70608017745019 L 683.4846176582525 101.70608017745019 C 684.9846176582525 101.70608017745019 686.4846176582525 103.20608017745019 686.4846176582525 104.70608017745019 L 686.4846176582525 173.879400308609 C 686.4846176582525 175.379400308609 684.9846176582525 176.879400308609 683.4846176582525 176.879400308609 L 659.6374603687633 176.879400308609 C 658.1374603687633 176.879400308609 656.6374603687633 175.379400308609 656.6374603687633 173.879400308609 Z " pathFrom="M 656.6374603687633 176.879400308609 L 656.6374603687633 176.879400308609 L 686.4846176582525 176.879400308609 L 686.4846176582525 176.879400308609 L 686.4846176582525 176.879400308609 L 686.4846176582525 176.879400308609 L 686.4846176582525 176.879400308609 L 656.6374603687633 176.879400308609 Z" cy="101.70508017745018" cx="686.4846176582525" j="9" val="340" barHeight="75.17332013115882" barWidth="29.847157289489243"></path><path d="M 731.2553535924864 173.879400308609 L 731.2553535924864 115.76098019673825 C 731.2553535924864 114.26098019673825 732.7553535924864 112.76098019673825 734.2553535924864 112.76098019673825 L 758.1025108819756 112.76098019673825 C 759.6025108819756 112.76098019673825 761.1025108819756 114.26098019673825 761.1025108819756 115.76098019673825 L 761.1025108819756 173.879400308609 C 761.1025108819756 175.379400308609 759.6025108819756 176.879400308609 758.1025108819756 176.879400308609 L 734.2553535924864 176.879400308609 C 732.7553535924864 176.879400308609 731.2553535924864 175.379400308609 731.2553535924864 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 731.2553535924864 173.879400308609 L 731.2553535924864 115.76098019673825 C 731.2553535924864 114.26098019673825 732.7553535924864 112.76098019673825 734.2553535924864 112.76098019673825 L 758.1025108819756 112.76098019673825 C 759.6025108819756 112.76098019673825 761.1025108819756 114.26098019673825 761.1025108819756 115.76098019673825 L 761.1025108819756 173.879400308609 C 761.1025108819756 175.379400308609 759.6025108819756 176.879400308609 758.1025108819756 176.879400308609 L 734.2553535924864 176.879400308609 C 732.7553535924864 176.879400308609 731.2553535924864 175.379400308609 731.2553535924864 173.879400308609 Z " pathFrom="M 731.2553535924864 176.879400308609 L 731.2553535924864 176.879400308609 L 761.1025108819756 176.879400308609 L 761.1025108819756 176.879400308609 L 761.1025108819756 176.879400308609 L 761.1025108819756 176.879400308609 L 761.1025108819756 176.879400308609 L 731.2553535924864 176.879400308609 Z" cy="112.75998019673824" cx="761.1025108819756" j="10" val="290" barHeight="64.11842011187076" barWidth="29.847157289489243"></path><path d="M 805.8732468162095 173.879400308609 L 805.8732468162095 98.07314016587735 C 805.8732468162095 96.57314016587735 807.3732468162095 95.07314016587735 808.8732468162095 95.07314016587735 L 832.7204041056988 95.07314016587735 C 834.2204041056988 95.07314016587735 835.7204041056988 96.57314016587735 835.7204041056988 98.07314016587735 L 835.7204041056988 173.879400308609 C 835.7204041056988 175.379400308609 834.2204041056988 176.879400308609 832.7204041056988 176.879400308609 L 808.8732468162095 176.879400308609 C 807.3732468162095 176.879400308609 805.8732468162095 175.379400308609 805.8732468162095 173.879400308609 Z " fill="rgba(5,150,105,0.85)" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area undefined" index="0" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 805.8732468162095 173.879400308609 L 805.8732468162095 98.07314016587735 C 805.8732468162095 96.57314016587735 807.3732468162095 95.07314016587735 808.8732468162095 95.07314016587735 L 832.7204041056988 95.07314016587735 C 834.2204041056988 95.07314016587735 835.7204041056988 96.57314016587735 835.7204041056988 98.07314016587735 L 835.7204041056988 173.879400308609 C 835.7204041056988 175.379400308609 834.2204041056988 176.879400308609 832.7204041056988 176.879400308609 L 808.8732468162095 176.879400308609 C 807.3732468162095 176.879400308609 805.8732468162095 175.379400308609 805.8732468162095 173.879400308609 Z " pathFrom="M 805.8732468162095 176.879400308609 L 805.8732468162095 176.879400308609 L 835.7204041056988 176.879400308609 L 835.7204041056988 176.879400308609 L 835.7204041056988 176.879400308609 L 835.7204041056988 176.879400308609 L 835.7204041056988 176.879400308609 L 805.8732468162095 176.879400308609 Z" cy="95.07214016587734" cx="835.7204041056988" j="11" val="370" barHeight="81.80626014273166" barWidth="29.847157289489243"></path><g class="apexcharts-bar-goals-markers"><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g><g className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskg0roks97)"></g></g><g class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g></g></g><g class="apexcharts-line-series apexcharts-plot-series"><g class="apexcharts-series" zIndex="1" seriesName="OutxOfxStock" data:longestSeries="true" rel="1" data:realIndex="1"><path d="M 0 163.61252028546332C 26.116262628303083 163.61252028546332 48.50163059542002 159.1905602777481 74.6178932237231 159.1905602777481C 100.73415585202619 159.1905602777481 123.11952381914313 165.82350028932095 149.2357864474462 165.82350028932095C 175.35204907574928 165.82350028932095 197.7374170428662 156.9795802738905 223.8536796711693 156.9795802738905C 249.9699422994724 156.9795802738905 272.3553102665893 171.57204829935074 298.4715728948924 171.57204829935074C 324.58783552319545 171.57204829935074 346.9732034903124 152.55762026617526 373.08946611861546 152.55762026617526C 399.20572874691857 152.55762026617526 421.5910967140355 161.40154028160572 447.7073593423386 161.40154028160572C 473.8236219706416 161.40154028160572 496.2089899377586 148.13566025846004 522.3252525660616 148.13566025846004C 548.4415151943648 148.13566025846004 570.8268831614816 143.7137002507448 596.9431457897848 143.7137002507448C 623.0594084180879 143.7137002507448 645.4447763852048 154.7686002700329 671.5610390135079 154.7686002700329C 697.6773016418109 154.7686002700329 720.0626696089279 159.1905602777481 746.1789322372309 159.1905602777481C 772.2951948655341 159.1905602777481 794.6805628326509 150.34664026231766 820.7968254609541 150.34664026231766" fill="none" fill-opacity="1" stroke="rgba(230,81,0,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="1" clip-path="url(#gridRectBarMaskg0roks97)" pathTo="M 0 163.61252028546332C 26.116262628303083 163.61252028546332 48.50163059542002 159.1905602777481 74.6178932237231 159.1905602777481C 100.73415585202619 159.1905602777481 123.11952381914313 165.82350028932095 149.2357864474462 165.82350028932095C 175.35204907574928 165.82350028932095 197.7374170428662 156.9795802738905 223.8536796711693 156.9795802738905C 249.9699422994724 156.9795802738905 272.3553102665893 171.57204829935074 298.4715728948924 171.57204829935074C 324.58783552319545 171.57204829935074 346.9732034903124 152.55762026617526 373.08946611861546 152.55762026617526C 399.20572874691857 152.55762026617526 421.5910967140355 161.40154028160572 447.7073593423386 161.40154028160572C 473.8236219706416 161.40154028160572 496.2089899377586 148.13566025846004 522.3252525660616 148.13566025846004C 548.4415151943648 148.13566025846004 570.8268831614816 143.7137002507448 596.9431457897848 143.7137002507448C 623.0594084180879 143.7137002507448 645.4447763852048 154.7686002700329 671.5610390135079 154.7686002700329C 697.6773016418109 154.7686002700329 720.0626696089279 159.1905602777481 746.1789322372309 159.1905602777481C 772.2951948655341 159.1905602777481 794.6805628326509 150.34664026231766 820.7968254609541 150.34664026231766" pathFrom="M 0 176.878400308609 L 0 176.878400308609 L 74.6178932237231 176.878400308609 L 149.2357864474462 176.878400308609 L 223.8536796711693 176.878400308609 L 298.4715728948924 176.878400308609 L 373.08946611861546 176.878400308609 L 447.7073593423386 176.878400308609 L 522.3252525660616 176.878400308609 L 596.9431457897848 176.878400308609 L 671.5610390135079 176.878400308609 L 746.1789322372309 176.878400308609 L 820.7968254609541 176.878400308609" fill-rule="evenodd"></path><g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="1"><g class="apexcharts-series-markers"><path d="M0,0" fill="#e65100" fill-opacity="1" stroke="#fff" stroke-opacity="0.9" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" cx="0" cy="0" shape="circle" class="apexcharts-marker w74x2bt1t" default-marker-size="0"></path></g></g></g><g class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realIndex="0"></g><g class="apexcharts-datalabels" data:realIndex="1"></g></g><line x1="-32.18811080239036" y1="0" x2="852.9849362633445" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="-32.18811080239036" y1="0" x2="852.9849362633445" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-xaxis" transform="translate(0, 0)"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text x="0" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jan</tspan><title>Jan</title></text><text x="74.61789322372309" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Feb</tspan><title>Feb</title></text><text x="149.2357864474462" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Mar</tspan><title>Mar</title></text><text x="223.85367967116932" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Apr</tspan><title>Apr</title></text><text x="298.47157289489246" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>May</tspan><title>May</title></text><text x="373.0894661186156" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jun</tspan><title>Jun</title></text><text x="447.7073593423387" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jul</tspan><title>Jul</title></text><text x="522.3252525660617" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Aug</tspan><title>Aug</title></text><text x="596.9431457897848" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Sep</tspan><title>Sep</title></text><text x="671.5610390135079" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Oct</tspan><title>Oct</title></text><text x="746.1789322372309" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Nov</tspan><title>Nov</title></text><text x="820.796825460954" y="204.878400308609" text-anchor="middle" dominant-baseline="auto" font-size="10px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Dec</tspan><title>Dec</title></text></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></svg><div class="apexcharts-legend" style="max-height: 120px;"></div><div class="apexcharts-sr-status" role="status" aria-live="polite" aria-atomic="true"></div><div class="apexcharts-tooltip apexcharts-theme-dark" role="tooltip" aria-live="polite" aria-atomic="true" data-placement="right" style="left: 71.8235px; top: 93.2784px; --apx-tt-arrow-y: 103.5999984741211px;" aria-hidden="true"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">Jan</div><div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0 apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(5, 150, 105);"><svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="6" cy="6" r="5" fill="currentColor"></circle></svg></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">Total Products: </span><span class="apexcharts-tooltip-text-y-value">220</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1 apexcharts-active" style="order: 2; display: flex;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(230, 81, 0);"><svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="6" cy="6" r="5" fill="currentColor"></circle></svg></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">Out Of Stock: </span><span class="apexcharts-tooltip-text-y-value">60</span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-dark" style="left: 47.3485px; top: 212.878px;"><div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; min-width: 16.1615px;">Jan</div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
							<div class="flex items-center justify-center gap-4 mt-1 text-[11px]">
								<div class="flex items-center gap-1">
									<span class="size-2 rounded-full bg-success"></span>
									<span class="text-default">Total Products</span>
								</div>
								<div class="flex items-center gap-1">
									<span class="size-2 rounded-full bg-orange"></span>
									<span class="text-default">Out Of Stock</span>
								</div>
							</div>
						</div>

					</div>

					<!-- Suppliers Warehouse -->
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-3">

						<!-- Suppliers -->
						<div class="bg-white border border-border-color rounded-md p-4 pb-3">
							<div class="flex items-center justify-between mb-3">
								<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Suppliers</h3>
								<a href="suppliers.html" class="btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-1 hover:bg-primary hover:text-white hover:border-primary">View All <i class="ph ph-caret-right text-[10px]"></i></a>
							</div>
							<div class="overflow-x-auto">
								<table class="w-full text-start border-collapse">
									<tbody>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="flex items-center gap-2 min-w-0">
													<div class="size-9 rounded-md bg-light flex items-center justify-center shrink-0">
														<i class="ph-duotone ph-desktop-tower text-warning"></i>
													</div>
													<div class="min-w-0">
														<p class="text-[11px] text-default mb-0"><a href="#">#LED0020</a></p>
														<p class="text-xs font-semibold text-title truncate mb-0">Apex Computers</p>
													</div>
												</div>
											</td>
											<td class="py-2 px-3">
												<p class="text-[11px] text-default mb-0">Goods Supplied</p>
												<p class="text-xs font-semibold text-title mb-0">$40,000</p>
											</td>
											<td class="py-2 ps-3 text-end">
												<span class="text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded inline-block whitespace-nowrap">Active</span>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="flex items-center gap-2 min-w-0">
													<div class="size-9 rounded-md bg-light flex items-center justify-center shrink-0">
														<i class="ph-duotone ph-headphones text-info"></i>
													</div>
													<div class="min-w-0">
														<p class="text-[11px] text-default mb-0"><a href="#">#LED0019</a></p>
														<p class="text-xs font-semibold text-title truncate mb-0">Beats Headphones</p>
													</div>
												</div>
											</td>
											<td class="py-2 px-3">
												<p class="text-[11px] text-default mb-0">Goods Supplied</p>
												<p class="text-xs font-semibold text-title mb-0">$34,000</p>
											</td>
											<td class="py-2 ps-3 text-end">
												<span class="text-[11px] bg-danger-transparent text-danger px-2 py-0.5 rounded inline-block whitespace-nowrap">Inactive</span>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="flex items-center gap-2 min-w-0">
													<div class="size-9 rounded-md bg-light flex items-center justify-center shrink-0">
														<i class="ph-duotone ph-sneaker text-pink"></i>
													</div>
													<div class="min-w-0">
														<p class="text-[11px] text-default mb-0"><a href="#">#LED0018</a></p>
														<p class="text-xs font-semibold text-title truncate mb-0">Dazzle Shoes</p>
													</div>
												</div>
											</td>
											<td class="py-2 px-3">
												<p class="text-[11px] text-default mb-0">Goods Supplied</p>
												<p class="text-xs font-semibold text-title mb-0">$32,000</p>
											</td>
											<td class="py-2 ps-3 text-end">
												<span class="text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded inline-block whitespace-nowrap">Active</span>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="flex items-center gap-2 min-w-0">
													<div class="size-9 rounded-md bg-light flex items-center justify-center shrink-0">
														<i class="ph-duotone ph-shopping-bag text-purple"></i>
													</div>
													<div class="min-w-0">
														<p class="text-[11px] text-default mb-0"><a href="#">#LED0017</a></p>
														<p class="text-xs font-semibold text-title truncate mb-0">Best Accessories</p>
													</div>
												</div>
											</td>
											<td class="py-2 px-3">
												<p class="text-[11px] text-default mb-0">Goods Supplied</p>
												<p class="text-xs font-semibold text-title mb-0">$27,000</p>
											</td>
											<td class="py-2 ps-3 text-end">
												<span class="text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded inline-block whitespace-nowrap">Active</span>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="flex items-center gap-2 min-w-0">
													<div class="size-9 rounded-md bg-light flex items-center justify-center shrink-0">
														<i class="ph-duotone ph-storefront text-success"></i>
													</div>
													<div class="min-w-0">
														<p class="text-[11px] text-default mb-0"><a href="#">#LED0016</a></p>
														<p class="text-xs font-semibold text-title truncate mb-0">A-Z Store</p>
													</div>
												</div>
											</td>
											<td class="py-2 px-3">
												<p class="text-[11px] text-default mb-0">Goods Supplied</p>
												<p class="text-xs font-semibold text-title mb-0">$13,000</p>
											</td>
											<td class="py-2 ps-3 text-end">
												<span class="text-[11px] bg-danger-transparent text-danger px-2 py-0.5 rounded inline-block whitespace-nowrap">Inactive</span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<!-- Warehouse -->
						<div class="bg-white border border-border-color rounded-md p-4 pb-3">
							<div class="flex items-center justify-between mb-3">
								<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Warehouse</h3>
								<a href="warehouse.html" class="btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-1 hover:bg-primary hover:text-white hover:border-primary">View All <i class="ph ph-caret-right text-[10px]"></i></a>
							</div>
							<div class="overflow-x-auto">
								<table class="w-full text-start border-collapse">
									<tbody>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="block min-w-0 group">
													<p class="text-[11px] text-default mb-0"><a href="#">#WRH0020</a></p>
													<p class="text-xs font-semibold text-title truncate mb-0">Smart Stock Hub</p>
												</div>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Contact Person</p>
												<p class="text-xs font-semibold text-title truncate mb-0">Ethan Walker</p>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Capacity</p>
												<p class="text-xs font-semibold text-title mb-0">30,000</p>
											</td>
											<td class="py-2 ps-3 text-end w-1">
												<div class="relative size-9">
													<svg class="size-full -rotate-90" viewBox="0 0 36 36">
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-light-500" stroke-width="3"></circle>
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-primary" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="15" stroke-linecap="round"></circle>
													</svg>
													<span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-900">85%</span>
												</div>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="block min-w-0 group hover:opacity-80 transition-opacity">
													<p class="text-[11px] text-default mb-0"><a href="#">#WRH0019</a></p>
													<p class="text-xs font-semibold text-title truncate mb-0">Flow Grid Storage</p>
												</div>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Contact Person</p>
												<p class="text-xs font-semibold text-title truncate mb-0">Madison Clark</p>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Capacity</p>
												<p class="text-xs font-semibold text-title mb-0">20,000</p>
											</td>
											<td class="py-2 ps-3 text-end w-1">
												<div class="relative size-9">
													<svg class="size-full -rotate-90" viewBox="0 0 36 36">
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-light-500" stroke-width="3"></circle>
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-orange" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="25" stroke-linecap="round"></circle>
													</svg>
													<span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-900">75%</span>
												</div>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="block min-w-0 group hover:opacity-80 transition-opacity">
													<p class="text-[11px] text-default mb-0"><a href="#">#WRH0018</a></p>
													<p class="text-xs font-semibold text-title truncate mb-0">Prime Storage Solutions</p>
												</div>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Contact Person</p>
												<p class="text-xs font-semibold text-title truncate mb-0">James Harris</p>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Capacity</p>
												<p class="text-xs font-semibold text-title mb-0">300,000</p>
											</td>
											<td class="py-2 ps-3 text-end w-1">
												<div class="relative size-9">
													<svg class="size-full -rotate-90" viewBox="0 0 36 36">
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-light-500" stroke-width="3"></circle>
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-warning" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="39" stroke-linecap="round"></circle>
													</svg>
													<span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-900">61%</span>
												</div>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="block min-w-0 group hover:opacity-80 transition-opacity">
													<p class="text-[11px] text-default mb-0"><a href="#">#WRH0017</a></p>
													<p class="text-xs font-semibold text-title truncate mb-0">Global Supply Depot</p>
												</div>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Contact Person</p>
												<p class="text-xs font-semibold text-title truncate mb-0">Avery Thompson</p>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Capacity</p>
												<p class="text-xs font-semibold text-title mb-0">25,000</p>
											</td>
											<td class="py-2 ps-3 text-end w-1">
												<div class="relative size-9">
													<svg class="size-full -rotate-90" viewBox="0 0 36 36">
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-light-500" stroke-width="3"></circle>
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-info" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="75" stroke-linecap="round"></circle>
													</svg>
													<span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-900">40%</span>
												</div>
											</td>
										</tr>
										<tr class="align-middle">
											<td class="py-2 pe-3">
												<div class="block min-w-0 group hover:opacity-80 transition-opacity">
													<p class="text-[11px] text-default mb-0"><a href="#">#WRH0016</a></p>
													<p class="text-xs font-semibold text-title truncate mb-0">Silverline Storage</p>
												</div>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Contact Person</p>
												<p class="text-xs font-semibold text-title truncate mb-0">Benjamin Wright</p>
											</td>
											<td class="py-2 px-3 min-w-0">
												<p class="text-[11px] text-default mb-0">Capacity</p>
												<p class="text-xs font-semibold text-title mb-0">16,000</p>
											</td>
											<td class="py-2 ps-3 text-end w-1">
												<div class="relative size-9">
													<svg class="size-full -rotate-90" viewBox="0 0 36 36">
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-light-500" stroke-width="3"></circle>
														<circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-danger" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="75" stroke-linecap="round"></circle>
													</svg>
													<span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-gray-900">32%</span>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>

					</div>

					<!-- Inventory Value -->
					<div class="bg-white border border-border-color rounded-md p-4 pb-0 mb-3">
						<div class="flex items-center justify-between mb-3">
							<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Inventory Value</h3>
							<div class="hs-dropdown [--placement:bottom-right] [--auto-close:inside] relative inline-flex">
                                <button type="button" class="hs-dropdown-toggle cursor-pointer btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-2 hover:bg-primary hover:border-primary hover:text-white focus:bg-primary focus:border-primary focus:text-white  focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    2026 <i class="icon-chevron-down"></i>
                                </button>

                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-50 bg-white border border-border-color shadow rounded-md mt-2 z-1" role="menu" aria-orientation="vertical" tabindex="-1">
                                    <div class="p-2 space-y-1"> 
                                        <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                            2026
                                        </a>
                                        <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                    		2025
                                        </a> 
                                        <a class="flex items-center px-2 py-1.5 rounded-md text-xs text-gray-900 hover:bg-light hover:text-gray-900 focus:outline-hidden focus:bg-white" href="#">
                                            2024
                                        </a> 
                                    </div>
                                </div>
                            </div>
						</div>
						<div id="inv-value-chart" class="w-full" style="min-height: 335px;"><div id="apexchartsp07hlvbq" class="apexcharts-canvas apexchartsp07hlvbq apexcharts-theme-light" style="width: 919px; height: 320px;"><svg xmlns="http://www.w3.org/2000/svg" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="919" height="320" role="application" aria-label="area chart with 1 data series: Inventory Value" tabindex="0"><foreignObject x="0" y="0" width="919" height="320"><style type="text/css">.apexcharts-flip-y {
  transform: scaleY(-1) translateY(-100%);
  transform-origin: top;
  transform-box: fill-box;
}
.apexcharts-flip-x {
  transform: scaleX(-1);
  transform-origin: center;
  transform-box: fill-box;
}
.apexcharts-legend {
  display: flex;
  overflow: auto;
  padding: 0 10px;
}
.apexcharts-legend.apexcharts-legend-group-horizontal {
  flex-direction: column;
}
.apexcharts-legend-group {
  display: flex;
}
.apexcharts-legend-group-vertical {
  flex-direction: column-reverse;
}
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
  flex-wrap: wrap
}
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  flex-direction: column;
  bottom: 0;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
  justify-content: flex-start;
  align-items: flex-start;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
  justify-content: center;
  align-items: center;
}
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
  justify-content: flex-end;
  align-items: flex-end;
}
.apexcharts-legend-series {
  cursor: pointer;
  line-height: normal;
  display: flex;
  align-items: center;
}
.apexcharts-legend-text {
  position: relative;
  font-size: 14px;
}
.apexcharts-legend-text *, .apexcharts-legend-marker * {
  pointer-events: none;
}
.apexcharts-legend-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-right: 1px;
}

.apexcharts-legend-series.apexcharts-no-click {
  cursor: auto;
}
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
  display: none !important;
}
.apexcharts-inactive-legend {
  opacity: 0.45;
} </style></foreignObject><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g class="apexcharts-yaxis" rel="0" transform="translate(0.6353511810302734, 0)"><g class="apexcharts-yaxis-texts-g"><text x="20" y="33.666666666666664" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>600</tspan><title>600</title></text><text x="20" y="76.264" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>550</tspan><title>550</title></text><text x="20" y="118.86133333333333" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>500</tspan><title>500</title></text><text x="20" y="161.45866666666666" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>450</tspan><title>450</title></text><text x="20" y="204.05599999999998" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>400</tspan><title>400</title></text><text x="20" y="246.6533333333333" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>350</tspan><title>350</title></text><text x="20" y="289.25066666666663" text-anchor="end" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>300</tspan><title>300</title></text></g></g><g class="apexcharts-inner apexcharts-graphical" transform="translate(45.63535118103027, 30)"><defs><clipPath id="gridRectMaskp07hlvbq"><rect x="-3" y="-3" width="858.1338872909546" height="261.584" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskp07hlvbq"><rect x="-3" y="-3" width="858.1338872909546" height="261.584" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskp07hlvbq"><rect x="-3" y="0" width="858.1338872909546" height="255.584" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskp07hlvbq"></clipPath><clipPath id="nonForecastMaskp07hlvbq"></clipPath><linearGradient id="SvgjsGradient16" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(5,150,105,0.4)" stop-opacity="0.4"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop><stop offset="1" stop-color="rgba(255,255,255,0)" stop-opacity="0"></stop></linearGradient></defs><line x1="0" y1="0" x2="0" y2="255.584" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="255.584" fill="#B1B9C4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal"><line x1="0" y1="42.59733333333333" x2="852.1338872909546" y2="42.59733333333333" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="85.19466666666666" x2="852.1338872909546" y2="85.19466666666666" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="127.792" x2="852.1338872909546" y2="127.792" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="170.38933333333333" x2="852.1338872909546" y2="170.38933333333333" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="212.98666666666665" x2="852.1338872909546" y2="212.98666666666665" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="255.58399999999997" x2="852.1338872909546" y2="255.58399999999997" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical"></g><line x1="0" y1="255.584" x2="852.1338872909546" y2="255.584" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="255.584" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders"><line x1="0" y1="0" x2="852.1338872909546" y2="0" stroke="var(--color-border-color)" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-area-series apexcharts-plot-series"><g class="apexcharts-series" zIndex="0" seriesName="InventoryxValue" data:longestSeries="true" rel="1" data:realIndex="0"><path d="M 0 238.54506666666668C 27.11335095925764 238.54506666666668 50.353366067192766 161.86986666666667 77.46671702645041 161.86986666666667C 104.58006798570806 161.86986666666667 127.82008309364318 187.42826666666667 154.93343405290082 187.42826666666667C 182.04678501215847 187.42826666666667 205.2868001200936 119.27253333333334 232.40015107935125 119.27253333333334C 259.51350203860886 119.27253333333334 282.753517146544 27.262293333333332 309.86686810580164 27.262293333333332C 336.9802190650593 27.262293333333332 360.2202341729944 153.35040000000004 387.3335851322521 153.35040000000004C 414.4469360915097 153.35040000000004 437.6869511994449 76.67520000000002 464.8003021587025 76.67520000000002C 491.9136531179601 76.67520000000002 515.1536682258952 102.23360000000002 542.2670191851529 102.23360000000002C 569.3803701444106 102.23360000000002 592.6203852523456 51.11680000000001 619.7337362116033 51.11680000000001C 646.847087170861 51.11680000000001 670.0871022787961 119.27253333333334 697.2004532380538 119.27253333333334C 724.3138041973115 119.27253333333334 747.5538193052465 68.15573333333333 774.6671702645042 68.15573333333333C 801.7805212237619 68.15573333333333 825.0205363316969 17.038933333333375 852.1338872909546 17.038933333333375C 852.1338872909546 17.038933333333375 852.1338872909546 17.038933333333375 852.1338872909546 255.584 L 0 255.584z" fill="url(#SvgjsGradient16)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskp07hlvbq)" pathTo="M 0 238.54506666666668C 27.11335095925764 238.54506666666668 50.353366067192766 161.86986666666667 77.46671702645041 161.86986666666667C 104.58006798570806 161.86986666666667 127.82008309364318 187.42826666666667 154.93343405290082 187.42826666666667C 182.04678501215847 187.42826666666667 205.2868001200936 119.27253333333334 232.40015107935125 119.27253333333334C 259.51350203860886 119.27253333333334 282.753517146544 27.262293333333332 309.86686810580164 27.262293333333332C 336.9802190650593 27.262293333333332 360.2202341729944 153.35040000000004 387.3335851322521 153.35040000000004C 414.4469360915097 153.35040000000004 437.6869511994449 76.67520000000002 464.8003021587025 76.67520000000002C 491.9136531179601 76.67520000000002 515.1536682258952 102.23360000000002 542.2670191851529 102.23360000000002C 569.3803701444106 102.23360000000002 592.6203852523456 51.11680000000001 619.7337362116033 51.11680000000001C 646.847087170861 51.11680000000001 670.0871022787961 119.27253333333334 697.2004532380538 119.27253333333334C 724.3138041973115 119.27253333333334 747.5538193052465 68.15573333333333 774.6671702645042 68.15573333333333C 801.7805212237619 68.15573333333333 825.0205363316969 17.038933333333375 852.1338872909546 17.038933333333375C 852.1338872909546 17.038933333333375 852.1338872909546 17.038933333333375 852.1338872909546 255.584 L 0 255.584z" pathFrom="M 0 255.584 L 0 255.584 L 77.46671702645041 255.584 L 154.93343405290082 255.584 L 232.40015107935125 255.584 L 309.86686810580164 255.584 L 387.3335851322521 255.584 L 464.8003021587025 255.584 L 542.2670191851529 255.584 L 619.7337362116033 255.584 L 697.2004532380538 255.584 L 774.6671702645042 255.584 L 852.1338872909546 255.584z"></path><path d="M 0 238.54506666666668C 27.11335095925764 238.54506666666668 50.353366067192766 161.86986666666667 77.46671702645041 161.86986666666667C 104.58006798570806 161.86986666666667 127.82008309364318 187.42826666666667 154.93343405290082 187.42826666666667C 182.04678501215847 187.42826666666667 205.2868001200936 119.27253333333334 232.40015107935125 119.27253333333334C 259.51350203860886 119.27253333333334 282.753517146544 27.262293333333332 309.86686810580164 27.262293333333332C 336.9802190650593 27.262293333333332 360.2202341729944 153.35040000000004 387.3335851322521 153.35040000000004C 414.4469360915097 153.35040000000004 437.6869511994449 76.67520000000002 464.8003021587025 76.67520000000002C 491.9136531179601 76.67520000000002 515.1536682258952 102.23360000000002 542.2670191851529 102.23360000000002C 569.3803701444106 102.23360000000002 592.6203852523456 51.11680000000001 619.7337362116033 51.11680000000001C 646.847087170861 51.11680000000001 670.0871022787961 119.27253333333334 697.2004532380538 119.27253333333334C 724.3138041973115 119.27253333333334 747.5538193052465 68.15573333333333 774.6671702645042 68.15573333333333C 801.7805212237619 68.15573333333333 825.0205363316969 17.038933333333375 852.1338872909546 17.038933333333375" fill="none" fill-opacity="1" stroke="#059669" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskp07hlvbq)" pathTo="M 0 238.54506666666668C 27.11335095925764 238.54506666666668 50.353366067192766 161.86986666666667 77.46671702645041 161.86986666666667C 104.58006798570806 161.86986666666667 127.82008309364318 187.42826666666667 154.93343405290082 187.42826666666667C 182.04678501215847 187.42826666666667 205.2868001200936 119.27253333333334 232.40015107935125 119.27253333333334C 259.51350203860886 119.27253333333334 282.753517146544 27.262293333333332 309.86686810580164 27.262293333333332C 336.9802190650593 27.262293333333332 360.2202341729944 153.35040000000004 387.3335851322521 153.35040000000004C 414.4469360915097 153.35040000000004 437.6869511994449 76.67520000000002 464.8003021587025 76.67520000000002C 491.9136531179601 76.67520000000002 515.1536682258952 102.23360000000002 542.2670191851529 102.23360000000002C 569.3803701444106 102.23360000000002 592.6203852523456 51.11680000000001 619.7337362116033 51.11680000000001C 646.847087170861 51.11680000000001 670.0871022787961 119.27253333333334 697.2004532380538 119.27253333333334C 724.3138041973115 119.27253333333334 747.5538193052465 68.15573333333333 774.6671702645042 68.15573333333333C 801.7805212237619 68.15573333333333 825.0205363316969 17.038933333333375 852.1338872909546 17.038933333333375" pathFrom="M 0 255.584 L 0 255.584 L 77.46671702645041 255.584 L 154.93343405290082 255.584 L 232.40015107935125 255.584 L 309.86686810580164 255.584 L 387.3335851322521 255.584 L 464.8003021587025 255.584 L 542.2670191851529 255.584 L 619.7337362116033 255.584 L 697.2004532380538 255.584 L 774.6671702645042 255.584 L 852.1338872909546 255.584" fill-rule="evenodd"></path><g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><path d="M 0, 0 
           m -0, 0 
           a 0,0 0 1,0 0,0 
           a 0,0 0 1,0 -0,0" fill="#059669" fill-opacity="1" stroke="#fff" stroke-opacity="0.9" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" cx="0" cy="0" shape="circle" class="apexcharts-marker wy1xc82ff no-pointer-events" default-marker-size="0"></path></g></g></g><g class="apexcharts-datalabels" data:realIndex="0"></g></g><line x1="0" y1="0" x2="852.1338872909546" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="852.1338872909546" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-xaxis" transform="translate(0, 0)"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text x="0" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jan</tspan><title>Jan</title></text><text x="77.46671702645043" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Feb</tspan><title>Feb</title></text><text x="154.93343405290085" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Mar</tspan><title>Mar</title></text><text x="232.40015107935125" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Apr</tspan><title>Apr</title></text><text x="309.86686810580164" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>May</tspan><title>May</title></text><text x="387.33358513225204" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jun</tspan><title>Jun</title></text><text x="464.80030215870244" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jul</tspan><title>Jul</title></text><text x="542.2670191851529" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Aug</tspan><title>Aug</title></text><text x="619.7337362116033" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Sep</tspan><title>Sep</title></text><text x="697.2004532380537" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Oct</tspan><title>Oct</title></text><text x="774.6671702645041" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Nov</tspan><title>Nov</title></text><text x="852.1338872909545" y="283.584" text-anchor="middle" dominant-baseline="auto" font-size="11px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#646973" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Dec</tspan><title>Dec</title></text></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect x="0" y="0" width="0" height="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></svg><div class="apexcharts-legend" style="max-height: 160px;"></div><div class="apexcharts-sr-status" role="status" aria-live="polite" aria-atomic="true"></div><div class="apexcharts-tooltip apexcharts-theme-dark" role="tooltip" aria-live="polite" aria-atomic="true" aria-hidden="true"><div class="apexcharts-tooltip-arrow"></div><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0" style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(5, 150, 105);"><svg viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="6" cy="6" r="5" fill="currentColor"></circle></svg></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-dark"><div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
					</div>

					<!-- Recent Stocks -->
					<div class="bg-white border border-border-color rounded-md p-4 pb-[6px]">
						<div class="flex items-center justify-between mb-3">
							<h3 class="text-lg max-lg:text-[17px] font-bold text-title mb-0">Recent Stocks</h3>
							<a href="manage-stock.html" class="btn-sm bg-white border border-border-color text-gray-900 inline-flex items-center gap-1 hover:bg-primary hover:text-white hover:border-primary">View All <i class="ph ph-caret-right text-[10px]"></i></a>
						</div>
						<div class="overflow-x-auto">
							<table class="w-full text-sm border-collapse">
								<thead>
									<tr class="text-xs text-default border-b border-border-color bg-light/50">
										<th class="text-left py-3 px-3 font-semibold text-gray-900">Code</th>
										<th class="text-left py-3 px-3 font-semibold text-gray-900">Product</th>
										<th class="text-left py-3 px-3 font-semibold text-gray-900">SKU</th>
										<th class="text-left py-3 px-3 font-semibold text-gray-900">Category</th>
										<th class="text-left py-3 px-3 font-semibold text-gray-900">Brand</th>
										<th class="text-left py-3 px-3 font-semibold text-gray-900">Unit</th>
										<th class="text-right py-3 px-3 font-semibold text-gray-900">Quantity</th>
										<th class="text-right py-3 px-3 font-semibold text-gray-900">Selling Price</th>
										<th class="text-right py-3 px-3 font-semibold text-gray-900">Purchase Price</th>
										<th class="text-center py-3 px-3 font-semibold text-gray-900">Status</th>
									</tr>
								</thead>
								<tbody>
									<tr class="border-b border-border-color last:border-0 hover:bg-light/20 transition-colors">
										<td class="py-3 px-3 text-xs text-default">
											<a href="#" class="hover:text-primary transition-colors font-medium">#PRD0020</a>
										</td>
										<td class="py-3 px-3">
											<div class="flex items-center gap-2">
												<div class="size-7 rounded-md bg-light flex items-center justify-center shrink-0">
													<i class="ph-fill ph-apple-logo text-gray-900"></i>
												</div>
												<a href="#" class="text-xs font-semibold text-title hover:text-primary transition-colors">Apple iPhone 15</a>
											</div>
										</td>
										<td class="py-3 px-3 text-xs text-default">APP-PH-15</td>
										<td class="py-3 px-3 text-xs text-default">Smartphones</td>
										<td class="py-3 px-3 text-xs text-default">Apple</td>
										<td class="py-3 px-3 text-xs text-default">Piece</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">02</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$250</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$230</td>
										<td class="py-3 px-3 text-center">
											<span class="inline-flex items-center text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded font-medium">
												In Stock <i class="ph ph-caret-down text-[10px] ms-1"></i>
											</span>
										</td>
									</tr>
									<!-- Row 2: Dell XPS 13 -->
									<tr class="border-b border-border-color last:border-0 hover:bg-light/20 transition-colors">
										<td class="py-3 px-3 text-xs text-default">
											<a href="#" class="hover:text-primary transition-colors font-medium">#PRD0019</a>
										</td>
										<td class="py-3 px-3">
											<div class="flex items-center gap-2">
												<div class="size-7 rounded-md bg-light flex items-center justify-center shrink-0">
													<i class="ph-duotone ph-laptop text-gray-900"></i>
												</div>
												<a href="#" class="text-xs font-semibold text-title hover:text-primary transition-colors">Dell XPS 13 9310</a>
											</div>
										</td>
										<td class="py-3 px-3 text-xs text-default">DEL-LAP-9310</td>
										<td class="py-3 px-3 text-xs text-default">Computers</td>
										<td class="py-3 px-3 text-xs text-default">Dell</td>
										<td class="py-3 px-3 text-xs text-default">Piece</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">12</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$300</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$280</td>
										<td class="py-3 px-3 text-center">
											<span class="inline-flex items-center text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded font-medium">
												In Stock <i class="ph ph-caret-down text-[10px] ms-1"></i>
											</span>
										</td>
									</tr>
									<tr class="border-b border-border-color last:border-0 hover:bg-light/20 transition-colors">
										<td class="py-3 px-3 text-xs text-default">
											<a href="#" class="hover:text-primary transition-colors font-medium">#PRD0018</a>
										</td>
										<td class="py-3 px-3">
											<div class="flex items-center gap-2">
												<div class="size-7 rounded-md bg-light flex items-center justify-center shrink-0">
													<i class="ph-duotone ph-headphones text-gray-900"></i>
												</div>
												<a href="#" class="text-xs font-semibold text-title hover:text-primary transition-colors">Bose QuietComfort 45</a>
											</div>
										</td>
										<td class="py-3 px-3 text-xs text-default">BOS-HD-45</td>
										<td class="py-3 px-3 text-xs text-default">Headphones</td>
										<td class="py-3 px-3 text-xs text-default">Bose</td>
										<td class="py-3 px-3 text-xs text-default">Piece</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">15</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$100</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$80</td>
										<td class="py-3 px-3 text-center">
											<span class="inline-flex items-center text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded font-medium">
												In Stock <i class="ph ph-caret-down text-[10px] ms-1"></i>
											</span>
										</td>
									</tr>
									<tr class="border-b border-border-color last:border-0 hover:bg-light/20 transition-colors">
										<td class="py-3 px-3 text-xs text-default">
											<a href="#" class="hover:text-primary transition-colors font-medium">#PRD0017</a>
										</td>
										<td class="py-3 px-3">
											<div class="flex items-center gap-2">
												<div class="size-7 rounded-md bg-light flex items-center justify-center shrink-0">
													<i class="ph-duotone ph-sneaker text-gray-900"></i>
												</div>
												<a href="#" class="text-xs font-semibold text-title hover:text-primary transition-colors">Adidas Running Shoe</a>
											</div>
										</td>
										<td class="py-3 px-3 text-xs text-default">ADI-SHO-RUN</td>
										<td class="py-3 px-3 text-xs text-default">Footwear</td>
										<td class="py-3 px-3 text-xs text-default">Adidas</td>
										<td class="py-3 px-3 text-xs text-default">Pack</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">20</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$400</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$380</td>
										<td class="py-3 px-3 text-center">
											<span class="inline-flex items-center text-[11px] bg-success-transparent text-success px-2 py-0.5 rounded font-medium">
												In Stock <i class="ph ph-caret-down text-[10px] ms-1"></i>
											</span>
										</td>
									</tr>
									<tr class="border-b border-border-color last:border-0 hover:bg-light/20 transition-colors">
										<td class="py-3 px-3 text-xs text-default">
											<a href="#" class="hover:text-primary transition-colors font-medium">#PRD0016</a>
										</td>
										<td class="py-3 px-3">
											<div class="flex items-center gap-2">
												<div class="size-7 rounded-md bg-light flex items-center justify-center shrink-0">
													<i class="ph-duotone ph-fan text-gray-900"></i>
												</div>
												<a href="#" class="text-xs font-semibold text-title hover:text-primary transition-colors">Dyson Vacuum Cleaner</a>
											</div>
										</td>
										<td class="py-3 px-3 text-xs text-default">DYS-VC-100</td>
										<td class="py-3 px-3 text-xs text-default">Appliances</td>
										<td class="py-3 px-3 text-xs text-default">Dyson</td>
										<td class="py-3 px-3 text-xs text-default">Piece</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">08</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$750</td>
										<td class="py-3 px-3 text-xs text-gray-900 text-right tabular-nums">$730</td>
										<td class="py-3 px-3 text-center">
											<span class="inline-flex items-center text-[11px] bg-danger-transparent text-danger px-2 py-0.5 rounded font-medium">
												Out of Stock <i class="ph ph-caret-down text-[10px] ms-1"></i>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>