import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(relativeTime)
dayjs.extend(utc)
dayjs.extend(timezone)

dayjs.tz.setDefault('UTC')

export default (el) => {
    let datetime = el.getAttribute('datetime')

    if (!datetime) {
        return
    }

    const setHumanTime = () => {
        el.innerHTML = `<time title="${dayjs().tz().to(dayjs.tz(datetime))}" datetime="${dayjs().tz().to(dayjs.tz(datetime))}">${dayjs().tz().to(dayjs.tz(datetime))}</time>`
    }

    setHumanTime()
    setInterval(setHumanTime, 30000)
}
