USE [THRecordDB]
GO

/****** Object:  StoredProcedure [dbo].[PHP_InsertChannelOpen]    Script Date: 02/13/2019 10:18:07 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO





ALTER PROC [dbo].[PHP_InsertChannelOpen]
	@ChannelID INT,							
	@TypeID INT,							
	@IP	NVARCHAR(15),
	@Area NVARCHAR(32)
 AS

-- 属性设置
SET NOCOUNT ON

-- 基本信息
DECLARE @RecordID int
DECLARE @Android_Scale int
DECLARE @Android_OutScale int
DECLARE @IOS_Scale int
DECLARE @IOS_OutScale int
DECLARE @today date
DECLARE @chann int
BEGIN

	select @Android_Scale=Android_Scale,@Android_OutScale=Android_OutScale,@IOS_Scale=IOS_Scale,@IOS_OutScale=IOS_OutScale,@chann=ChannelID from THRecordDB.DBO.RecordChannelList(nolock) where ChannelID=@ChannelID and Deleted =0 and Status = 0
	-- IF @Android_Scale IS NULL 
	IF @chann IS NULL 
	BEGIN
		RETURN 1
	END
	
	set @today=GETDATE()

	INSERT INTO THRecordDB.DBO.RecordChannelOpen (ChannelID,TypeID,IP,Area) values(@ChannelID,@TypeID,@IP,@Area)
	SELECT @RecordID=RecordID from THRecordDB.DBO.RecordChannelDayCount where ChannelID=@ChannelID and InsertDate=@today and TypeID=@TypeID
	IF @RecordID IS NOT NULL
	BEGIN
		UPDATE THRecordDB.DBO.RecordChannelDayCount SET [Open]=[Open]+1 where RecordID=@RecordID
		update [THRecordDB].[dbo].[RecordChannelList] set [Open_sum] = [Open_sum] + 1 where [ChannelID] = @ChannelID
	END
	ELSE
	BEGIN
		IF @TypeID=1
		BEGIN
			INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,[Open],Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,1,@IOS_Scale,@IOS_OutScale,@today,@TypeID)
		END
		IF @TypeID=2
		BEGIN
			INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,[Open],Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,1,@Android_Scale,@Android_OutScale,@today,@TypeID)
		END
		IF @TypeID=3
		BEGIN
			INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,[Open],Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,1,@Android_Scale,@Android_OutScale,@today,@TypeID)
		END

	END
	
	RETURN 0
END

GO


