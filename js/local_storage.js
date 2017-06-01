var SORTING_TABLE_TEAM = 'TEAM';
var SORTING_TABLE_USER = 'USER';

var SORTING_COLUMN = '_col';
var SORTING_DIRECTION = '_dir';

function storeSorting(id,col,dir)
{
    if (!localStorageExists()) {
    	return false;
    }
    localStorage.setItem(id+SORTING_COLUMN, col);
    localStorage.setItem(id+SORTING_DIRECTION, dir);    
    
}

function readSorting(id)
{
    if (!localStorageExists()) {
    	return null;
    }
    
    var sorting = new Array();
    
    sorting[0] = localStorage.getItem(id+SORTING_COLUMN);
    sorting[1] = localStorage.getItem(id+SORTING_DIRECTION);
    
    if (isUndefined(sorting[0]))
    {
        return null;
    }
    if (isUndefined(sorting[1]))
    {
        return null;
    }  
    
    
    return sorting;
}



function storeLocalStorage(id,data)
{
    
}

function localStorageExists()
{
    try {
	return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
	return false;
    }
}
