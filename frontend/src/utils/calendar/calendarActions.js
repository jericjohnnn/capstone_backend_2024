

const isEventOverlap = (newStart, newEnd, event) => {
  const existingStartTime = new Date(event.start).getTime();
  const existingEndTime = new Date(event.end).getTime();

  // Check if the new slot overlaps with the existing slot
  return newStart < existingEndTime && newEnd > existingStartTime;
};

export const checkEventOverlap = (newStart, newEnd, events) => {
  const hasOverlap = events.some(event =>
    isEventOverlap(newStart, newEnd, event)
  );
  if (hasOverlap) {
    return true
  }
  return false
};

export const formatNewEvent = (selectedDate, startTime, endTime) => {
  const newEvent = {
    id: Date.now(),
    start: `${selectedDate} ${startTime}`,
    end: `${selectedDate} ${endTime}`,
    title: 'You Added',
    class: 'addedSchedule',
    deletable: true,
  };
  return { newEvent };
};

export const getBusinessHours = (tutorStartTime, tutorEndTime) => {
  if (
    (tutorStartTime < 12 && tutorEndTime <= 12) ||
    (tutorStartTime >= 13 && tutorEndTime <= 23)
  ) {
    return [
      {
        from: tutorStartTime * 60,
        to: tutorEndTime * 60,
        class: 'business-hours',
      },
    ];
  }

  return [
    { from: tutorStartTime * 60, to: 12 * 60, class: 'business-hours' },
    { from: 13 * 60, to: tutorEndTime * 60, class: 'business-hours' },
  ];
};
