#!/bin/bash
export LC_TIME=en_US.UTF-8

#Sar directory
SARPATH="/var/log/sa"

#Parsed logs directory
PARSEDPATH="/var/cache/sargraph"

[ -d ${PARSEDPATH} ] || mkdir -p ${PARSEDPATH}

#CPU log name
CPULOGFILE="cpulog"

#RAM log file
RAMLOGFILE="ramlog"

#Load log file
LOADLOGFILE="loadlog"

#temp log
TEMPLOG=$PARSEDPATH"/tmplog"

#purge existing logs
> $PARSEDPATH/$CPULOGFILE
> $PARSEDPATH/$RAMLOGFILE
> $PARSEDPATH/$LOADLOGFILE

for i in `ls -rt $SARPATH | grep -v sar | grep -v parsed`
do
	FULLPATH=$SARPATH/$i
	LOGDATE=`sar -f $FULLPATH | head -1 | awk '{print $4}' | awk -F'/' '{print $2"/"$1"/"$3}'`
	sar -f $FULLPATH | grep ":" | grep -v "Average" | grep -v "idle" | grep -v "LINUX RESTART" > $TEMPLOG
	cat $TEMPLOG | while read LINE
	do
		LOGTIME=`echo $LINE | awk '{print $1,$2}'`
		LOGTIME=`date --date "$LOGTIME" +%H:%M`
		LOGVALUE=`echo $LINE | awk '{print 100-$9","$7}'`
		echo $LOGDATE,$LOGTIME,$LOGVALUE >> $PARSEDPATH/$CPULOGFILE
	done
        sar -r -f $FULLPATH | grep ":" | grep -v "Average" | grep -v "kbmemfree" | grep -v "LINUX RESTART" > $TEMPLOG
        cat $TEMPLOG | while read LINE
        do
                LOGTIME=`echo $LINE | awk '{print $1,$2}'`
                LOGTIME=`date --date "$LOGTIME" +%H:%M`
                LOGVALUE=`echo $LINE | awk '{ totalmem = $3+$4; memused = $3+$6+$7; pctused = (1 - memused/totalmem)*100; print pctused","$10}'`
                echo $LOGDATE,$LOGTIME,$LOGVALUE >> $PARSEDPATH/$RAMLOGFILE
        done
        sar -q -f $FULLPATH | grep ":" | grep -v "Average" | grep -v "runq" | grep -v "LINUX RESTART" > $TEMPLOG
        cat $TEMPLOG | while read LINE
        do
                LOGTIME=`echo $LINE | awk '{print $1,$2}'`
                LOGTIME=`date --date "$LOGTIME" +%H:%M`
                LOGVALUE=`echo $LINE | awk '{ print $5","$6","$7 }'`
                echo $LOGDATE,$LOGTIME,$LOGVALUE >> $PARSEDPATH/$LOADLOGFILE
        done
done
