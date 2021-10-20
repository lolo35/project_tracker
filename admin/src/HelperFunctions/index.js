function parseDate(date){
    let dDate = new Date(date);
    let year = dDate.getFullYear();
    let month = dDate.getMonth() + 1;
    if(month.toString().length < 2){
        month = `0${month}`;
    }
    let day = dDate.getDate();
    if(day.toString().length < 2){
        day = `0${day}`;
    }

    let hour = dDate.getHours();
    if(hour.toString().legnth < 2){
        hour = `0${hour}`;
    }

    let minute = dDate.getMinutes();
    if(minute.toString().length < 2){
        minute = `0${minute}`;
    }

    return `${year}-${month}-${day} ${hour}:${minute}`;
}

export { parseDate }