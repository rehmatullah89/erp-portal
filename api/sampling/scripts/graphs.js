
var objGraph;

function loaded( )
{
	objGraph = new iScroll('Graph', { hScroll:false, hScrollbar:false, vScroll:true, vScrollbar:true, hideScrollbar:false });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
