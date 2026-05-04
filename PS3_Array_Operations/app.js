const processBtn = document.getElementById("processBtn");
const searchBtn = document.getElementById("searchBtn");
const reverseBtn = document.getElementById("reverseBtn");
const inputs = document.querySelectorAll(".inputele");

let sortedArr = [];

function bubbleSort(arr) {
    let a = [];
    for (let i = 0; i < arr.length; i++) {
        a.push(arr[i]);
    }

    for (let i = 0; i < a.length; i++) {
        for (let j = 0; j < a.length - i - 1; j++) {
            if (a[j] > a[j + 1]) {
                let temp = a[j];
                a[j] = a[j + 1];
                a[j + 1] = temp;
            }
        }
    }
    return a;
}

function mergeSort(arr) {
    if (arr.length <= 1) {
        return arr;
    }

    let mid = Math.floor(arr.length / 2);
    let left = [];
    let right = [];

    for (let i = 0; i < mid; i++) {
        left.push(arr[i]);
    }
    for (let i = mid; i < arr.length; i++) {
        right.push(arr[i]);
    }

    left = mergeSort(left);
    right = mergeSort(right);

    return merge(left, right);
}

function merge(left, right) {
    let result = [];
    let i = 0, j = 0;

    while (i < left.length && j < right.length) {
        if (left[i] < right[j]) {
            result.push(left[i]);
            i++;
        } else {
            result.push(right[j]);
            j++;
        }
    }

    while (i < left.length) {
        result.push(left[i]);
        i++;
    }

    while (j < right.length) {
        result.push(right[j]);
        j++;
    }

    return result;
}

processBtn.addEventListener("click", () => {

    let arr = [];
    sortedArr = [];

    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value !== "") {
            arr.push(parseInt(inputs[i].value));
        }
    }

    let sortType = document.querySelector('input[name="sortType"]:checked').value;

    if (sortType === "bubble") {
        sortedArr = bubbleSort(arr);
    } else {
        sortedArr = mergeSort(arr);
    }

    document.getElementById("sort").innerHTML = sortedArr.join(", ");
    document.getElementById("reverse").innerHTML = "";
    document.getElementById("searchResult").innerHTML = "";
});

searchBtn.addEventListener("click", () => {

    let key = parseInt(document.getElementById("searchKey").value);
    let found = false;
    let position = -1;

    for (let i = 0; i < sortedArr.length; i++) {
        if (sortedArr[i] === key) {
            found = true;
            position = i;
            break;
        }
    }

    if (found) {
        document.getElementById("searchResult").innerHTML =
            "Element " + key + " found at position " + (position + 1) + " of sorted array";
    } else {
        document.getElementById("searchResult").innerHTML =
            "Element " + key + " not found in the array";
    }
});

reverseBtn.addEventListener("click", () => {

    let reversedArr = [];

    for (let i = sortedArr.length - 1; i >= 0; i--) {
        reversedArr.push(sortedArr[i]);
    }

    document.getElementById("reverse").innerHTML = reversedArr.join(", ");
});