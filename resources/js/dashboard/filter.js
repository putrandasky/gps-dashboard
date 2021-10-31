import Vue from "vue"

Vue.filter("first4Chars", str => str.substring(0, 4))
Vue.filter("ordinal", function(i) {
  var j = i % 10,
    k = i % 100;
  if (j == 1 && k != 11) {
    return i + "st";
  }
  if (j == 2 && k != 12) {
    return i + "nd";
  }
  if (j == 3 && k != 13) {
    return i + "rd";
  }
  return i + "th";
})
Vue.filter("last4Chars", str => str.substring(str.length - 4))
Vue.filter("noData", str => str ? str : 'No Data')
Vue.filter("_toSpace", str => str.replace(/_/g, ' '))
Vue.filter("ucfirst", str => str.toLowerCase().split(' ').map((s) => s.charAt(0).toUpperCase() + s.substring(1)).join(' '))
Vue.filter('formatSize', function(size) {
  if (size > 1024 * 1024 * 1024 * 1024) {
    return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
  } else if (size > 1024 * 1024 * 1024) {
    return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
  } else if (size > 1024 * 1024) {
    return (size / 1024 / 1024).toFixed(2) + ' MB'
  } else if (size > 1024) {
    return (size / 1024).toFixed(2) + ' KB'
  }
  return size.toString() + ' B'
})
Vue.filter('reverse', function(value) {
  // slice to make a copy of array, then reverse the copy
  return value.slice().reverse();
});
Vue.filter('dateFormated', function(value) {
  // slice to make a copy of array, then reverse the copy
  return value ? dayjs(value).format('D-MMM-YY') : '';
});
Vue.filter('currency', function(value) {
  let val = (value / 1).toFixed(0).replace('.', ',')
  return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
});

Vue.filter('trimHtml', function(str) {
  if ((str === null) || (str === ''))
    return false;
  else
    str = str.toString();
  return str.replace(/(<([^>]+)>)/ig, '');

})
