export default {
	isObject,
	notObject,
	objectSlice,
	objectClone,
	arrayRemoveByValue,
	isNotEmpty,
	isEmpty,
	isEqual,
	someIsTrue,
	someIsFalse,
	allTrue,
	isValidUrl,
	isFunction,
	isNestingExist,
	getNesting,
	getUrlParams,
	getProviderFilters
}

export function isObject(x) {
	return typeof x === 'object' && x !== null;
};

export function notObject(x) {
	return !isObject(x);
};

export function objectSlice(obj, key) {
	if (!obj.hasOwnProperty(key))
		return false;

	const keyValue = obj[key];
	delete obj[key];

	return keyValue;
};

export function objectClone(obj) {
	return Object.assign(Object.create(Object.getPrototypeOf(obj)), obj);
};

export function arrayRemoveByValue(array, val) {
	let index = array.indexOf(val);

	if (index > -1) {
		array.splice(index, 1);
	}
}

export function isNotEmpty(obj) {
	switch (obj.constructor) {
		case Object:
			return Object.entries(obj).length ? true : false;
		case Array:
			return obj.length ? true : false;
	}

	return obj ? true : false;
}

export function isEmpty(obj) {
	return !isNotEmpty(obj);
}

export function someIsTrue(arr) {
	return arr.some(item => {
		return Boolean(item);
	});
}

export function someIsFalse(arr) {
	return arr.some(item => {
		return !Boolean(item);
	});
}

export function allTrue(arr) {
	return someIsFalse(arr) ? false : true;
}

export function isValidUrl(string) {
	try {
		new URL(string);
	} catch (_) {
		return false;
	}

	return true;
}

export function isFunction(variableToCheck) {
	return variableToCheck instanceof Function ? true : false;
}

export function isNestingExist(obj) {
	const nesting = Array.from(arguments).splice(1);
	let output = true;

	for (let key of nesting) {
		if (!obj[key]) {
			output = false
			break;
		}

		obj = obj[key];
	}

	return output;
}

export function getNesting(obj) {
	const nesting = Array.from(arguments).splice(1);
	let isNestingExist = true;

	for (let key of nesting) {
		if (!obj[key]) {
			isNestingExist = false
			break;
		}

		obj = obj[key];
	}

	return isNestingExist ? obj : false;
}

export function isEqual(value, other) {
	let type = Object.prototype.toString.call(value);

	if (type !== Object.prototype.toString.call(other)) {
		return false;
	}

	if (['[object Array]', '[object Object]'].indexOf(type) < 0) {
		return false;
	}

	let valueLen = type === '[object Array]' ? value.length : Object.keys(value).length,
		otherLen = type === '[object Array]' ? other.length : Object.keys(other).length;

	if (valueLen !== otherLen) {
		return false;
	}

	let compare = function (item1, item2) {
		let itemType = Object.prototype.toString.call(item1);

		if (['[object Array]', '[object Object]'].indexOf(itemType) >= 0) {
			if (!isEqual(item1, item2)) {
				return false;
			}
		} else {
			if (itemType !== Object.prototype.toString.call(item2)) {
				return false;
			}

			if (itemType === '[object Function]') {
				if (item1.toString() !== item2.toString()) {
					return false;
				}
			} else {
				if (item1 !== item2) {
					return false;
				}
			}
		}
	};

	if (type === '[object Array]') {
		for (let i = 0; i < valueLen; i++) {
			if (compare(value[i], other[i]) === false) {
				return false;
			}
		}
	} else {
		for (let key in value) {
			if (value.hasOwnProperty(key)) {
				if (compare(value[key], other[key]) === false) {
					return false;
				}
			}
		}
	}

	return true;
};

export function getUrlParams() {
	const search = decodeURIComponent(window.location.search),
		hashes = search.slice(search.indexOf('?') + 1).split('&'),
		params = {};

	hashes.map(hash => {
		const [key, val] = hash.split('=');
		params[key] = val;
	})

	return params;
}

export function getProviderFilters(provider, queryId = 'default') {
	return getNesting(JetSmartFilters, 'filterGroups', provider + '/' + queryId, 'filters') || [];
}